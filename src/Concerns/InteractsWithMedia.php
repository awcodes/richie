<?php

namespace Awcodes\Richie\Concerns;

use Closure;
use Filament\Forms\Components\BaseFileUpload;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Set;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

trait InteractsWithMedia
{
    protected ?array $acceptedFileTypes = null;

    protected string | Closure | null $directory = null;

    protected string | Closure | null $disk = null;

    protected string | Closure | null $imageCropAspectRatio = null;

    protected string | Closure | null $imageResizeMode = null;

    protected string | Closure | null $imageResizeTargetHeight = null;

    protected string | Closure | null $imageResizeTargetWidth = null;

    protected int | Closure | null $maxSize = null;

    protected int | Closure | null $minSize = null;

    protected bool | Closure | null $relativePaths = null;

    protected bool | Closure | null $shouldPreserveFileNames = null;

    protected string | Closure | null $visibility = null;

    protected FileUpload | Field | Closure | null $uploader = null;

    public function acceptedFileTypes(array $acceptedFileTypes): static
    {
        $this->acceptedFileTypes = $acceptedFileTypes;

        return $this;
    }

    public function directory(string | Closure $directory): static
    {
        $this->directory = $directory;

        return $this;
    }

    public function disk(string | Closure $disk): static
    {
        $this->disk = $disk;

        return $this;
    }

    public function imageCropAspectRatio(string | Closure | null $ratio): static
    {
        $this->imageCropAspectRatio = $ratio;

        return $this;
    }

    public function imageResizeMode(string | Closure | null $mode): static
    {
        $this->imageResizeMode = $mode;

        return $this;
    }

    public function imageResizeTargetHeight(string | Closure | null $height): static
    {
        $this->imageResizeTargetHeight = $height;

        return $this;
    }

    public function imageResizeTargetWidth(string | Closure | null $width): static
    {
        $this->imageResizeTargetWidth = $width;

        return $this;
    }

    public function maxSize(int | Closure $size): static
    {
        $this->maxSize = $size;

        return $this;
    }

    public function minSize(int | Closure $size): static
    {
        $this->minSize = $size;

        return $this;
    }

    public function preserveFileNames(bool | Closure $shouldPreserveFileNames): static
    {
        $this->shouldPreserveFileNames = $shouldPreserveFileNames;

        return $this;
    }

    public function visibility(string | Closure $visibility): static
    {
        $this->visibility = $visibility;

        return $this;
    }

    public function relativePaths(bool | Closure $relativePaths = true): static
    {
        $this->relativePaths = $relativePaths;

        return $this;
    }

    public function uploader(FileUpload | Field | Closure $uploader): static
    {
        $this->uploader = $uploader;

        return $this;
    }

    public function getAcceptedFileTypes(): array
    {
        return $this->acceptedFileTypes ?? config('richie.media.accepted_file_types');
    }

    public function getDirectory(): string
    {
        return $this->evaluate($this->directory) ?? config('richie.media.directory');
    }

    public function getDisk(): string
    {
        return $this->evaluate($this->disk) ?? config('richie.media.disk');
    }

    public function getImageCropAspectRatio(): ?string
    {
        return $this->evaluate($this->imageCropAspectRatio) ?? config('richie.media.image_crop_aspect_ratio');
    }

    public function getImageResizeMode(): ?string
    {
        return $this->evaluate($this->imageResizeMode) ?? config('richie.media.image_resize_mode');
    }

    public function getImageResizeTargetHeight(): ?string
    {
        return $this->evaluate($this->imageResizeTargetHeight) ?? config('richie.media.image_resize_target_height');
    }

    public function getImageResizeTargetWidth(): ?string
    {
        return $this->evaluate($this->imageResizeTargetWidth) ?? config('richie.media.image_resize_target_width');
    }

    public function getMaxSize(): int
    {
        return $this->evaluate($this->maxSize) ?? config('richie.media.max_size');
    }

    public function getMinSize(): int
    {
        return $this->evaluate($this->minSize) ?? config('richie.media.min_size');
    }

    public function getUploader(): FileUpload | Field
    {
        return $this->evaluate($this->uploader) ?? FileUpload::make('src')
            ->label(fn () => trans('richie::richie.media.src'))
            ->disk($this->getDisk())
            ->directory($this->getDirectory())
            ->visibility($this->getVisibility())
            ->preserveFilenames($this->shouldPreserveFileNames())
            ->acceptedFileTypes($this->getAcceptedFileTypes())
            ->maxFiles(1)
            ->maxSize($this->getMaxSize())
            ->minSize($this->getMinSize())
            ->imageResizeMode($this->getImageResizeMode())
            ->imageCropAspectRatio($this->getImageCropAspectRatio())
            ->imageResizeTargetWidth($this->getImageResizeTargetWidth())
            ->imageResizeTargetHeight($this->getImageResizeTargetHeight())
            ->required()
            ->live()
            ->afterStateUpdated(function (TemporaryUploadedFile $state, Set $set): void {
                if (Str::contains($state->getMimeType(), 'image')) {
                    $set('type', 'image');
                    if (! Str::contains($state->getMimeType(), 'svg')) {
                        $set('width', $state->dimensions()[0]);
                        $set('height', $state->dimensions()[1]);
                    } else {
                        $set('width', 50);
                        $set('height', 50);
                    }
                } else {
                    $set('type', 'document');
                }
            })
            ->saveUploadedFileUsing(function (BaseFileUpload $component, TemporaryUploadedFile $file): string {
                $filename = $component->shouldPreserveFilenames()
                    ? pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)
                    : Str::uuid();
                $storeMethod = $component->getVisibility() === 'public' ? 'storePubliclyAs' : 'storeAs';
                $extension = $file->getClientOriginalExtension();
                $storage = Storage::disk($component->getDiskName());

                if ($storage->exists(ltrim($component->getDirectory() . '/' . $filename . '.' . $extension, '/'))) {
                    $filename = $filename . '-' . time();
                }

                $upload = $file->{$storeMethod}($component->getDirectory(), $filename . '.' . $extension, $component->getDiskName());

                return $storage->url($upload);
            });
    }

    public function getVisibility(): string
    {
        return $this->visibility ? $this->evaluate($this->visibility) : config('richie.media.visibility');
    }

    public function shouldPreserveFileNames(): bool
    {
        return $this->shouldPreserveFileNames ? $this->evaluate($this->shouldPreserveFileNames) : config('richie.media.preserve_file_names');
    }

    public function useRelativePaths(): bool
    {
        return $this->evaluate($this->relativePaths) ?? false;
    }
}
