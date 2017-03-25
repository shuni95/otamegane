<?php

namespace App\Services\ApiScrappers;

use Carbon\Carbon;
use App\Source;
use App\Notification;
use App\TelegramChat;
use App\Subscription;
use Telegram\Bot\Api as TelegramSender;
use App\Services\MessengerService as MessengerSender;

class TuMangaOnlineScrapper
{
    protected $mangas;

    public function __construct()
    {
        $this->source = Source::where('name', 'TuMangaOnline')->first();
        $this->mangas = $this->source->mangas->pluck('name');
    }

    public function scrapping()
    {
        $recent = true;
        $per_page = 50;
        $page = 1;

        while ($recent) {
            $last_updates = $this->getLastUpdates($per_page, $page);
            $data = collect($last_updates['data']);
            $yesterday = Carbon::yesterday();

            $data->each(function ($update) use ($yesterday, &$recent) {
                $manga    = $update['capitulo']['tomo']['manga']['nombre'];
                $id_manga = $update['capitulo']['tomo']['idManga'];
                $chapter  = $update['capitulo']['numCapitulo'];
                $id_scan  = $update['idScan'];

                $created_at = $update['fechaPublicacion'];

                if ($this->identifyManga($manga)) {

                    if (Carbon::parse($created_at) >= $yesterday) {

                        if ($this->dontExistsPreviousNotification($manga, $chapter)) {
                            $manga_url = $update['capitulo']['tomo']['manga']['nombreUrl'];

                            $url = $this->makeLink($manga_url, $id_manga, $chapter, $id_scan);

                            $notification = Notification::create([
                                'manga'     => $manga,
                                'chapter'   => $chapter,
                                'title'     => $chapter,
                                'status'    => 'WIP',
                                'url'       => $url,
                                'source_id' => $this->source->id,
                            ]);

                            $notification->sendSubscribers(
                                $this->getTelegramSubscribers($manga),
                                new TelegramSender
                            );

                            $notification->sendSubscribers(
                                $this->getMessengerSubscribers($manga),
                                new MessengerSender
                            );

                            $notification->status = 'DONE';
                            $notification->save();
                        }

                    } else {
                        $recent = false;
                    }
                }
            });

            $page++;
            if ($last_updates['last_page'] < $page) {
                $recent = false;
            }
        }
    }

    public function getLastUpdates($per_page, $page)
    {
        $url = $this->parseUrl($per_page, $page);

        return json_decode(file_get_contents($url) , true);
    }

    public function parseUrl($itemsPerPage, $page)
    {
        $typeFilter = 'MANGA';

        return "http://www.tumangaonline.com/api/v1/subidas?".http_build_query(compact('itemsPerPage', 'page', 'typeFilter'));
    }

    public function makeLink($manga, $id_manga, $num_capitulo, $id_scan)
    {
        return "http://www.tumangaonline.com/lector/".$manga."/".$id_manga."/".$num_capitulo."/".$id_scan;
    }

    public function identifyManga($manga)
    {
        return $this->mangas->first(function ($manga_name) use ($manga) {
            return $manga_name == $manga;
        });
    }

    public function dontExistsPreviousNotification($manga, $chapter)
    {
        return is_null(Notification::previous($manga, $chapter, $this->source->id)->first());
    }

    public function getSubscribers($manga)
    {
        return TelegramChat::subscribedTo($manga, $this->source->id)->pluck('chat_id');
    }

    protected function getTelegramSubscribers($manga)
    {
        return Subscription::ofTelegram($manga, $this->source->id)->get();
    }

    protected function getMessengerSubscribers($manga)
    {
        return Subscription::ofMessenger($manga, $this->source->id)->get();
    }
}
