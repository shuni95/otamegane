<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Manga::class, function () {
    return [
        'name' => 'TestManga',
    ];
});

$factory->define(App\Source::class, function () {
    return [
        'name' => 'TestSource',
        'url'  => 'TestUrl',
    ];
});

$factory->define(App\MangaSource::class, function () use ($factory) {
    return [
        'manga_id'  => factory(App\Manga::class)->create()->id,
        'source_id' => factory(App\Source::class)->create()->id
    ];
});

$factory->define(App\TelegramChat::class, function (Faker\Generator $faker) {
    return [
        'chat_id' => $faker->randomNumber(9),
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'username' => $faker->userName,
        'title' => null,
        'type'=> 'private',
    ];
});

$factory->define(App\MessengerChat::class, function (Faker\Generator $faker) {
    return [
        'chat_id' => $faker->randomNumber(9),
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'locale' => $faker->locale,
        'gender' => $faker->randomElement(['male', 'female']),
    ];
});

$factory->define(App\Subscription::class, function (Faker\Generator $faker) use ($factory) {
    return [
        'manga_source_id' => 1,
        'telegram_chat_id' => null
    ];
});

$factory->state(App\Subscription::class, 'telegram', function () {
    return [
        'telegram_chat_id' => factory(App\TelegramChat::class)->create()->chat_id
    ];
});

$factory->state(App\Subscription::class, 'messenger', function () {
    return [
        'messenger_chat_id' => factory(App\MessengerChat::class)->create()->chat_id
    ];
});
