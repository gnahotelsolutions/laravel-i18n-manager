<?php

namespace GNAHotelSolutions\LaravelI18nManager\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Translation\Translator;
use Symfony\Component\Finder\SplFileInfo;

class ExportCommand extends Command
{
    protected $signature = 'i18n:export';

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

        $this->prepareFile();

        $this->getAllTranslationKeys()->each(
            fn(string $key) => $this->writeToFile([$key, ...$this->translateAll($key)])
        );

        $this->closeFile();

        $this->info("Your translations file is ready. You can find it at {$this->getFilePath()}");
    }

    protected function loadLocalesFromDirectories(): void
    {
        $this->locales = collect($this->fs->directories($this->path))->map(fn(string $path) => Str::afterLast($path, '/'));
    }

    protected function prepareFile(): void
    {
        $this->file = fopen($this->getFilePath(), 'w');

        fprintf($this->file, "\xEF\xBB\xBF");

        $this->writeToFile(['key', ...$this->locales]);
    }

    protected function writeToFile(array $row): void
    {
        fputcsv($this->file, $row, ';');
    }

    protected function closeFile(): void
    {
        fclose($this->file);
    }

    protected function getFilePath(): string
    {
        return storage_path('translations.csv');
    }

    protected function getAllTranslationKeys(): Collection
    {
        return $this->locales->flatMap(fn(string $locale) => $this->getTranslationsForLocale($locale))->filter()->keys()->sort();
    }

    protected function getTranslationsForLocale(string $locale): Collection
    {
        return $this->getFilesForLocale($locale)->flatMap(fn(SplFileInfo $file) => $this->getFileContent($file));
    }

    protected function getFilesForLocale(string $locale): Collection
    {
        return collect($this->fs->files("{$this->path}/{$locale}"));
    }

    protected function getFileContent(SplFileInfo $file): array
    {
        return Arr::dot(
            include($file),
            "{$file->getFilenameWithoutExtension()}."
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
