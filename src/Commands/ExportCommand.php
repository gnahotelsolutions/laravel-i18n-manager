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

    public function __construct(protected Filesystem $fs, protected Translator $translator)
    {
        parent::__construct();

        $this->path = app('path.lang');
        $this->locales = collect();
    }

    public function handle()
    {
        $this->locales = $this->getLocalesFromDirectories();

        $rows = $this->getCsvHeader()
            ->merge($this->getAllTranslationKeys()->map(
                fn(string $key) => [$key, ...$this->convertKeyToTranslations($key)]
            ));

        // TODO: Save the CSV file as {project name}.csv

    }

    protected function getLocalesFromDirectories(): Collection
    {
        return collect($this->fs->directories($this->path))->map(fn(string $path) => Str::afterLast($path, '/'));
    }

    protected function getCsvHeader(): Collection
    {
        return collect(['key', ...$this->locales]);
    }

    protected function getAllTranslationKeys(): Collection
    {
        return $this->locales->flatMap(fn(string $locale) => $this->getTranslationsForLocale($locale))->filter()->keys();
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

    protected function convertKeyToTranslations(string $key): Collection
    {
        return $this->locales->map(fn(string $locale) => $this->convertKeyToTranslation($key, $locale));
    }

    protected function convertKeyToTranslation(string $key, string $locale): string
    {
        return $this->translator->has($key, $locale, false)
            ? $this->translator->get($key, [], $locale)
            : '';
    }
}
