<?php

namespace Adiartawibawa\LanguageGenerator\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Adiartawibawa\LanguageGenerator\Helpers\TranslationHelper;

class LanguageGenerator extends Command
{
    // Command signature with arguments and options
    protected $signature = 'lang:generate {from} {to*} {--file=} {--json}';

    // Command description
    protected $description = 'Generate and Translate language files from one language to another using Google Translate';

    /**
     * Execute the console command.
     *
     * This is the main method that gets executed when the command is run.
     */
    public function handle()
    {
        // Get the 'from' language argument
        $from = $this->argument('from');

        // Get the 'to' languages arguments
        $targets = $this->argument('to');

        // Get the optional 'file' option
        $specificFile = $this->option('file');

        // Get the optional 'json' option
        $onlyJson = $this->option('json');

        // Determine the source path for the language files
        $sourcePath = "lang/{$from}";

        // Check if the source is a directory or a JSON file
        if (!$onlyJson && !File::isDirectory($sourcePath)) {
            $this->error("The source language directory does not exist: {$sourcePath}");
            return;
        }

        if ($onlyJson) {
            $sourcePath = "lang/{$from}.json";
            if (!File::isFile($sourcePath)) {
                $this->error("The source language json file does not exist: {$sourcePath}");
                return;
            }
        }

        // Process the source based on whether it's a JSON file or a directory
        if ($onlyJson) {
            $this->processJsonFile($sourcePath, $from, $targets);
        } else {
            $this->processDirectory($sourcePath, $from, $targets, $specificFile);
        }

        // Inform the user that all files have been translated
        $this->info("\n\n All files have been translated. \n");
    }

    /**
     * Process a JSON file and translate its contents.
     *
     * @param string $sourceFile The source JSON file path.
     * @param string $from The source language code.
     * @param array|string $targets The target language codes.
     */
    protected function processJsonFile(string $sourceFile, string $from, array|string $targets): void
    {
        foreach ($targets as $to) {
            $this->info("\n\n 🤖 Translating to '{$to}'");

            // Get the contents of the JSON file
            $translations = json_decode(File::get($sourceFile), true, 512, JSON_THROW_ON_ERROR);

            // Create a progress bar
            $bar = $this->output->createProgressBar(count($translations));
            $bar->setFormat(config('language-generator.progress_bar_format', " %current%/%max% [%bar%] %percent:3s%% -- %message%"));
            $bar->setMessage('Initializing...');
            $bar->start();

            $bar->setMessage("🔄 Processing: {$sourceFile}");
            $bar->display();

            // Translate the array contents
            $translated = $this->translateArray($translations, $from, $to, $bar);

            // Save the translated contents to the target path
            $targetPath = "lang/{$to}.json";
            $outputContent = json_encode($translated, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            File::put($targetPath, $outputContent);

            $bar->setMessage("✅");
        }

        $bar->finish();
    }

    /**
     * Process a directory of language files and translate their contents.
     *
     * @param string $sourcePath The source directory path.
     * @param string $from The source language code.
     * @param array|string $targets The target language codes.
     * @param bool|array|string|null $specificFile The specific file to process, if any.
     */
    protected function processDirectory(string $sourcePath, string $from, array|string $targets, bool|array|string|null $specificFile): void
    {
        $filesToProcess = [];

        // Determine which files to process
        if ($specificFile) {
            $filePath = $sourcePath . '/' . $specificFile;
            if (!File::exists($filePath)) {
                $this->error("The specified file does not exist: {$filePath}");
                return;
            }
            $filesToProcess[] = ['path' => $filePath, 'relativePathname' => $specificFile];
        } else {
            foreach (File::allFiles($sourcePath) as $file) {
                $filesToProcess[] = ['path' => $file->getPathname(), 'relativePathname' => $file->getRelativePathname()];
            }
        }

        foreach ($targets as $to) {
            $this->info("\n\n 🔔 Translating to '{$to}'");

            // Create a progress bar
            $bar = $this->output->createProgressBar(count($filesToProcess));
            $bar->setFormat(config('language-generator.progress_bar_format', " %current%/%max% [%bar%] %percent:3s%% -- %message%"));
            $bar->setMessage('Initializing...');
            $bar->start();

            foreach ($filesToProcess as $fileInfo) {
                $filePath = $fileInfo['relativePathname'];

                $bar->setMessage("🔄 Processing: {$filePath}");
                $bar->display();

                // Load translations from the PHP file
                $translations = include $fileInfo['path'];
                $translated = $this->translateArray($translations, $from, $to);

                // Save the translated contents to the target directory
                $targetPath = "lang/{$to}/" . dirname($filePath);
                if (!File::isDirectory($targetPath)) {
                    File::makeDirectory($targetPath, 0755, true, true);
                }

                $outputFile = "{$targetPath}/" . basename($filePath);
                $outputContent = "<?php\n\nreturn " . TranslationHelper::arrayToString($translated) . ";\n";
                File::put($outputFile, $outputContent);

                $bar->advance();

                $bar->setMessage("✅");
            }

            $bar->finish();
        }
    }

    /**
     * Translate the contents of an array recursively.
     *
     * @param mixed $content The content to translate.
     * @param string $source The source language code.
     * @param string $target The target language code.
     * @param mixed $bar The progress bar instance (optional).
     * @return mixed The translated content.
     */
    protected function translateArray($content, $source, $target, $bar = null)
    {
        if (is_array($content)) {
            foreach ($content as $key => $value) {
                $content[$key] = $this->translateArray($value, $source, $target);
                $bar?->advance();
            }
            return $content;
        } else if ($content === '' || $content === null) {
            $this->error("Translation value missing, make sure all translation values are not empty, in source file!");
            exit();
        } else {
            return TranslationHelper::translate($content, $source, $target);
        }
    }
}
