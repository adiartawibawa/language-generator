# Language Generator

`language-generator` is a Laravel package that allows you to generate and translate language files from one language to another using Google Translate.

## Installation

You can install the package via Composer:

```bash
composer require adiartawibawa/language-generator
```

### Service Provider Registration

If you are using Laravel 5.5 or later, the service provider will be automatically registered. For earlier versions of Laravel, you need to manually add the service provider in your `config/app.php` file:

```php
'providers' => [
    // Other service providers...
    Adiartawibawa\LanguageGenerator\LanguageGeneratorServiceProvider::class,
],
```

### Configuration

To publish the configuration file, run the following command:

```bash
php artisan vendor:publish --provider="Adiartawibawa\LanguageGenerator\LanguageGeneratorServiceProvider" --tag="config"
```

This will create a `language-generator.php` configuration file in your `config` directory. You can customize the configuration as needed.

## Usage

Once the package is installed and configured, you can use it via Artisan commands to generate and translate language files.

### Commands

The package provides an Artisan command for generating and translating language files.

#### Generating and Translating Language Files

To generate and translate language files from one language to another, use the following command:

```bash
php artisan lang:generate {from} {to*} {--file=} {--json}
```

**Arguments:**

-   `{from}`: The source language code.
-   `{to*}`: The target language codes (you can specify multiple target languages).

**Options:**

-   `--file=`: (Optional) Specify a particular file to translate.
-   `--json`: (Optional) If specified, the source language file is assumed to be a JSON file.

**Examples:**

-   Generate and translate all language files from English to Spanish and French:

    ```bash
    php artisan lang:generate en es fr
    ```

-   Translate a specific file from English to Spanish:

    ```bash
    php artisan lang:generate en es --file=messages.php
    ```

-   Translate a JSON file from English to Spanish:

    ```bash
    php artisan lang:generate en es --json
    ```

### Example Workflow

1. **Generating and Translating Language Files**

    Generate and translate language files from English to Spanish and French:

    ```bash
    php artisan lang:generate en es fr
    ```

    This will create Spanish and French language files with translated strings.

2. **Translating a Specific File**

    Translate the `messages.php` file from English to Spanish:

    ```bash
    php artisan lang:generate en es --file=messages.php
    ```

3. **Translating a JSON File**

    Translate the `lang/en.json` file to Spanish:

    ```bash
    php artisan lang:generate en es --json
    ```

## Configuration Options

The `language-generator.php` configuration file contains various options that you can customize:

```php
return [
    /*
    |--------------------------------------------------------------------------
    | Google Translate API Endpoint
    |--------------------------------------------------------------------------
    |
    | This value determines the endpoint used to communicate with the Google
    | Translate API. You may change this value to any other endpoint if necessary.
    |
    */
    'api_endpoint' => 'https://translate.googleapis.com/translate_a/single?client=gtx',

    /*
    |--------------------------------------------------------------------------
    | Default Source Language
    |--------------------------------------------------------------------------
    |
    | This value determines the default source language code that will be used
    | when generating and translating language files if no source language is specified.
    |
    */
    'default_source_language' => 'en',

    /*
    |--------------------------------------------------------------------------
    | Default Target Languages
    |--------------------------------------------------------------------------
    |
    | This value determines the default target languages that will be used
    | when generating and translating language files if no target languages are specified.
    |
    */
    'default_target_languages' => ['fr', 'es', 'de'],

    /*
    |--------------------------------------------------------------------------
    | Retry Settings
    |--------------------------------------------------------------------------
    |
    | These values determine the retry settings when making requests to the Google
    | Translate API. You can specify the number of retries and the interval between retries.
    |
    */
    'retry_attempts' => 3,
    'retry_interval' => 100, // in milliseconds

    /*
    |--------------------------------------------------------------------------
    | Progress Bar Settings
    |--------------------------------------------------------------------------
    |
    | This value determines the format of the progress bar displayed during the
    | translation process.
    |
    */
    'progress_bar_format' => ' %current%/%max% [%bar%] %percent:3s%% -- %message%',
];
```

## License

This package is open-source and licensed under the MIT license.

## Troubleshooting

If you encounter any issues, ensure you have the necessary configurations set up in the `language-generator.php` configuration file. Check for any API limitations or errors from Google Translate as well.

## Contributing

Contributions are welcome! Please open an issue or submit a pull request for any bugs or feature requests.

## Contact

For any questions or inquiries, please contact Adi Arta Wibawa at surat.buat.adi@gmail.com.
