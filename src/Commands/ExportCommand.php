<?php

namespace GNAHotelSolutions\LaravelI18nManager\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Translation\Translator;

class ExportCommand extends Command
{
    protected $signature = 'i18n:export {--F|file=}';

    protected $description = 'Export your translations into a single CSV file.';

    public string $path;
    public Collection $locales;

    public $file;

    public function __construct(protected Filesystem $fs, protected Translator $translator)
    {
        parent::__construct();

        $this->path = app('path.lang');
        $this->locales = collect();
    }

    public function handle()
    {
        $this->loadLocalesFromDirectories();

        $this->createOutputFile();

        $this->getAllTranslationKeys()->each(
            fn(string $key) => $this->writeOutput([$key, ...$this->translateAll($key)])
        );

        $this->closeFile();

        $this->info("Your translations file is ready. You can find it at {$this->getOutputPath()}");
    }

    protected function loadLocalesFromDirectories(): void
    {
        $this->locales = collect($this->fs->directories($this->path))->map(fn(string $path) => Str::afterLast($path, '/'));
    }

    protected function createOutputFile(): void
    {
        $this->file = fopen($this->getOutputPath(), 'w');

        fprintf($this->file, "\xEF\xBB\xBF");

        $this->writeOutput(['key', ...$this->locales]);
    }

    protected function writeOutput(array $row): void
    {
        fputcsv($this->file, $row, ';');
    }

    protected function closeFile(): void
    {
        fclose($this->file);
    }

    protected function getOutputPath(): string
    {
        return storage_path('translations.csv');
    }

    protected function getAllTranslationKeys(): Collection
    {
        return $this->locales->flatMap(fn(string $locale) => $this->getTranslationsForLocale($locale))->filter()->keys()->sort();
    }

    protected function getTranslationsForLocale(string $locale): Collection
    {
        return $this->getFilesForLocale($locale)->flatMap(fn(\SplFileInfo $file) => $this->getFileContent($file));
    }

    protected function getFilesForLocale(string $locale): Collection
    {
        if ($this->hasOption('file')) {
            return file_exists($this->getFileForLocale($locale))
                ? collect([new \SplFileInfo($this->getFileForLocale($locale))])
                : collect();
        }

        return collect($this->fs->files("{$this->path}/{$locale}"));
    }

    protected function getFileForLocale(string $locale): string
    {
        return "{$this->path}/{$locale}/{$this->option('file')}.php";
    }

    protected function getFileContent(\SplFileInfo $file): array
    {
        return Arr::dot(
            include($file),
            pathinfo($file->getFilename(), \PATHINFO_FILENAME)."."
        );
    }

    protected function translateAll(string $key): Collection
    {
        return $this->locales->map(fn(string $locale) => $this->translate($key, $locale));
    }

    protected function translate(string $key, string $locale): string
    {
        return $this->translator->has($key, $locale, false)
            ? $this->translator->get($key, [], $locale)
            : '';
    }
}
