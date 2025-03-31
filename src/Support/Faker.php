<?php

namespace Awcodes\Richie\Support;

use Faker\Factory;
use Faker\Generator;
use Illuminate\Support\Str;

class Faker
{
    protected Generator $faker;

    protected string $output = '';

    final public function __construct()
    {
        // Prevent direct instantiation
        // Use the static make() method instead
    }

    public static function make(): static
    {
        $static = new static;
        $static->faker = Factory::create();

        return $static;
    }

    public function heading(int | string | null $level = 2): static
    {
        $this->output .= '<h' . $level . '>' . Str::title($this->faker->words(mt_rand(3, 8), true)) . '</h' . $level . '>';

        return $this;
    }

    public function emptyParagraph(): static
    {
        $this->output .= '<p></p>';

        return $this;
    }

    public function paragraphs(int $count = 1, bool $withRandomLinks = false): static
    {
        if ($withRandomLinks) {
            $this->output .= '<p>' . collect($this->faker->paragraphs($count))->map(function ($paragraph) {
                $pos = mt_rand(3, strlen($paragraph));

                $start = substr($paragraph, 0, $pos);
                $end = substr($paragraph, $pos);

                $link = ' <a href="' . $this->faker->url() . '">' . $this->faker->words(mt_rand(3, 8), true) . '</a> ';

                return $start . $link . $end;
            })->implode('</p><p>') . '</p>';
        } else {
            $this->output .= '<p>' . collect($this->faker->paragraphs($count))->implode('</p><p>') . '</p>';
        }

        return $this;
    }

    public function lead(int $paragraphs = 1): static
    {
        $this->output .= '<div class="lead"><p>' . collect($this->faker->paragraphs($paragraphs))->implode('</p><p>') . '</p></div>';

        return $this;
    }

    public function small(): static
    {
        $this->output .= '<p><small>' . $this->faker->words(mt_rand(3, 8), true) . '</small></p>';

        return $this;
    }

    public function unorderedList(int $count = 1): static
    {
        $this->output .= '<ul><li>' . collect($this->faker->paragraphs($count))->implode('</li><li>') . '</li></ul>';

        return $this;
    }

    public function orderedList(int $count = 1): static
    {
        $this->output .= '<ol><li>' . collect($this->faker->paragraphs($count))->implode('</li><li>') . '</li></ol>';

        return $this;
    }

    public function image(?string $source = null, ?int $width = 640, ?int $height = 480): static
    {
        if (! $source) {
            $source = $this->faker->imageUrl($width, $height);
        }

        $this->output .= '<img src="' . $source . '" alt="' . $this->faker->sentence . '" title="' . $this->faker->sentence . '" width="' . $width . '" height="' . $height . '" />';

        return $this;
    }

    public function link(): static
    {
        $this->output .= '<a href="' . $this->faker->url() . '">' . $this->faker->words(mt_rand(3, 8), true) . '</a>';

        return $this;
    }

    public function embed(?string $provider = 'youtube', ?int $width = 1600, ?int $height = 900, bool $responsive = true): static
    {
        $style = $responsive ? 'aspect-ratio:1600/900; width: 100%; height: auto;' : '';
        $responsive = $responsive ? 'responsive' : '';

        if ($provider === 'vimeo') {
            $this->output .= '<div data-vimeo-video="true" class="' . $responsive . '"><iframe src="https://vimeo.com/146782320" width="' . $width . '" height="' . $height . '" allow="autoplay; fullscreen; picture-in-picture" style="' . $style . '"></iframe></div>';
        } else {
            $this->output .= '<div data-youtube-video="true" class="' . $responsive . '"><iframe src="https://www.youtube.com/watch?v=4ugMYpzLA0c" width="' . $width . '" height="' . $height . '" allow="autoplay; fullscreen; picture-in-picture" style="' . $style . '"></iframe></div>';
        }

        return $this;
    }

    public function details(bool $open = false): static
    {
        $this->output .= '<details' . ($open ? ' open' : null) . '><summary>' . $this->faker->sentence() . '</summary><div data-type="details-content"><p>' . $this->faker->paragraph() . '</p></div></details>';

        return $this;
    }

    public function code(?string $className = null): static
    {
        $this->output .= "<code class=\"{$className}\">" . $this->faker->words(mt_rand(3, 5), true) . '</code>';

        return $this;
    }

    public function codeBlock(?string $language = 'sh'): static
    {
        $this->output .= "<pre><code class=\"language-{$language}\">export default function testComponent({\n\tstate,\n}) {\n\treturn {\n\t\tstate,\n\t\tinit: function () {\n\t\t\t// Initialize the Alpine component here, if you need to.\n\t\t},\n\t}\n}</code></pre>";

        return $this;
    }

    public function blockquote(): static
    {
        $this->output .= '<blockquote>' . '<p>' . $this->faker->paragraph() . '</p>' . '</blockquote>';

        return $this;
    }

    public function hr(): static
    {
        $this->output .= '<hr>';

        return $this;
    }

    public function br(): static
    {
        $this->output .= '<br>';

        return $this;
    }

    public function table(?int $cols = null): static
    {
        $cols = $cols ?? mt_rand(3, 8);

        $this->output .= '<table><thead><tr><th>' . collect($this->faker->words($cols))->implode('</th><th>') . '</th></tr></thead><tbody><tr><td>' . collect($this->faker->words($cols))->implode('</td><td>') . '</td></tr><tr><td>' . collect($this->faker->words($cols))->implode('</td><td>') . '</td></tr></tbody></table>';

        return $this;
    }

    public function grid(array $cols = [1, 1, 1]): static
    {
        $this->output .= '<div class="richie-grid" data-type="responsive" data-columns="' . count($cols) . '" style="grid-template-columns: repeat(' . count($cols) . ', 1fr);" data-stack-at="md">';

        foreach ($cols as $col) {
            $this->output .= '<div class="richie-grid-column" data-col-span="' . $col . '" style="grid-column: span 1;"><h2>' . Str::title($this->faker->words(mt_rand(3, 8), true)) . '</h2><p>' . $this->faker->paragraph() . '</p></div>';
        }

        $this->output .= '</div>';

        return $this;
    }

    public function block(string $identifier, ?array $values = []): static
    {
        $this->output .= '<richie-block>' . json_encode(['identifier' => $identifier, 'values' => $values]) . '</richie-block>';

        return $this;
    }

    public function sink(): static
    {
        $this
            ->heading()
            ->paragraphs(2, true)
            ->unorderedList(3)
            ->orderedList(3)
            ->hr()
            ->grid()
            ->image()
            ->blockquote();

        return $this;
    }

    public function asHtml(): string
    {
        return $this->output;
    }

    public function asJson(): array
    {
        return (new Converter($this->output))->toJson();
    }

    public function asText(): string
    {
        return (new Converter($this->output))->toText();
    }
}
