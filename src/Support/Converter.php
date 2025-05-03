<?php

namespace Awcodes\Richie\Support;

use Awcodes\Richie\Facades\Richie;
use Closure;
use Filament\Support\Concerns\EvaluatesClosures;
use League\HTMLToMarkdown\HtmlConverter;
use stdClass;
use Tiptap\Editor;

class Converter
{
    use EvaluatesClosures;

    protected Editor $editor;

    protected array | Closure | null $mergeTagsMap = null;

    public function __construct(
        public string | array | stdClass | null $content = null,
    ) {
        if ($this->content instanceof stdClass) {
            $this->content = json_decode(json_encode($this->content), true);
        }
    }

    public function convert(string | array $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function mergeTagsMap(array | Closure $mergeTagsMap): static
    {
        $this->mergeTagsMap = $mergeTagsMap;

        return $this;
    }

    public function getEditor(): Editor
    {
        return $this->editor ??= new Editor([
            'extensions' => [
                new \Tiptap\Nodes\Document,
                new \Tiptap\Nodes\Text,
                new \Tiptap\Nodes\HardBreak,
                new \Tiptap\Nodes\Paragraph,
                new \Awcodes\Richie\Tiptap\Extensions\Classes,
                new \Awcodes\Richie\Tiptap\Extensions\Ids,
                new \Awcodes\Richie\Tiptap\Nodes\ListItem,
                new \Awcodes\Richie\Tiptap\Nodes\RichieBlock,
                new \Awcodes\Richie\Tiptap\Nodes\MergeTag,
                new \Awcodes\Richie\Tiptap\Nodes\Mention,
                ...$this->getExtensions(),
            ],
        ]);
    }

    public function getExtensions(): array
    {
        return collect(Richie::getActions())
            ->filter(fn ($action) => $action->getConverterExtensions())
            ->map(fn ($action) => $action->getConverterExtensions())
            ->flatten()
            ->mapWithKeys(fn ($extension) => [$extension::class => $extension])
            ->unique()
            ->values()
            ->toArray();
    }

    public function toHtml(bool $toc = false, int $maxDepth = 3, bool $wrapHeadings = false): string
    {
        if (blank($this->content) || $this->content === '') {
            return '';
        }

        $editor = $this->getEditor()->setContent($this->content);

        if ($toc) {
            $this->parseHeadings($editor, $maxDepth, $wrapHeadings);
        }

        if (filled($this->getMergeTagsMap())) {
            $this->parseMergeTags($editor);
        }

        $this->parseMentionItems($editor);

        /*
         * Temporary fix for Tiptap Serializer bug duplicating code block tags
         */
        return str_replace('</code></pre></code></pre>', '</code></pre>', $editor->getHTML());
    }

    public function toJson(bool $toc = false, int $maxDepth = 3): array
    {
        if (blank($this->content) || $this->content === '') {
            return [];
        }

        $editor = $this->getEditor()->setContent($this->content);

        $this->sanitizeBlocks($editor);

        if ($toc) {
            $this->parseHeadings($editor, $maxDepth);
        }

        $this->parseMentionItems($editor);

        return json_decode($editor->getJSON(), true);
    }

    public function toText(): string
    {
        if (blank($this->content) || $this->content === '') {
            return '';
        }

        return $this->getEditor()->setContent($this->content)->getText();
    }

    public function toMarkdown(bool $toc = false, int $maxDepth = 3, ?array $options = []): string
    {
        if (blank($this->content) || $this->content === '') {
            return '';
        }

        return (new HtmlConverter($options))
            ->convert($this->toHtml(toc: $toc, maxDepth: $maxDepth));
    }

    public function toTOC(int $maxDepth = 3, bool $array = false): string | array
    {
        if (blank($this->content) || $this->content === '') {
            return '';
        }

        if (is_string($this->content)) {
            $this->content = $this->toJson();
        }

        $headings = $this->parseTocHeadings($this->content['content'], $maxDepth);

        return $array ?
            $this->generateTOCArray($headings) :
            $this->generateNestedTOC($headings, $headings[0]['level']);
    }

    public function sanitizeBlocks(Editor $editor): Editor
    {
        $editor->descendants(function (&$node): void {
            if ($node->type !== 'richieBlock') {
                return;
            }

            unset($node->content);
        });

        return $editor;
    }

    public function parseHeadings(Editor $editor, int $maxDepth = 3, bool $wrapHeadings = false): Editor
    {
        $editor->descendants(function (&$node) use ($maxDepth, $wrapHeadings): void {
            if ($node->type !== 'heading') {
                return;
            }

            if ($node->attrs->level > $maxDepth) {
                return;
            }

            if (! property_exists($node->attrs, 'id') || $node->attrs->id === null) {
                $node->attrs->id = str(collect($node->content)->map(fn ($node) => $node->text ?? null)->implode(' '))->slug()->toString();
            }

            if ($wrapHeadings) {
                $text = str(collect($node->content)->map(fn ($node) => $node->text ?? null)->implode(' '))->toString();

                $node->content = [
                    (object) [
                        'type' => 'text',
                        'marks' => [
                            [
                                'type' => 'link',
                                'attrs' => [
                                    'href' => '#' . $node->attrs->id,
                                    'class' => 'toc-link',
                                ],
                            ],
                        ],
                        'text' => $text,
                    ],
                ];
            } else {
                array_unshift($node->content, (object) [
                    'type' => 'text',
                    'text' => '#',
                    'marks' => [
                        [
                            'type' => 'link',
                            'attrs' => [
                                'href' => '#' . $node->attrs->id,
                                'class' => 'toc-link',
                            ],
                        ],
                    ],
                ]);
            }
        });

        return $editor;
    }

    public function parseTocHeadings(array $content, int $maxDepth = 3): array
    {
        $headings = [];

        foreach ($content as $node) {
            if ($node['type'] === 'heading') {
                if ($node['attrs']['level'] <= $maxDepth) {
                    $text = collect($node['content'])->map(fn ($node): mixed => $node['text'] ?? null)->implode(' ');

                    if (! isset($node['attrs']['id'])) {
                        $node['attrs']['id'] = str($text)->slug()->toString();
                    }

                    $headings[] = [
                        'level' => $node['attrs']['level'],
                        'id' => $node['attrs']['id'],
                        'text' => $text,
                    ];
                }
            } elseif (array_key_exists('content', $content)) {
                $this->parseTocHeadings($content, $maxDepth);
            }
        }

        return $headings;
    }

    public function generateTOCArray(array &$headings, int $parentLevel = 0): array
    {

        $result = [];

        foreach ($headings as $key => &$value) {
            $currentLevel = $value['level'];
            $nextLevel = $headings[$key + 1]['level'] ?? 0;

            if ($parentLevel >= $currentLevel) {
                break;
            }

            unset($headings[$key]);

            $heading = [
                'id' => $value['id'],
                'text' => $value['text'],
                'depth' => $currentLevel,
            ];

            if ($nextLevel > $currentLevel) {
                $heading['subs'] = $this->generateTOCArray($headings, $currentLevel);
            }

            $result[] = $heading;

        }

        return $result;

    }

    public function generateNestedTOC(array $headings, int $parentLevel = 0): string
    {
        $result = '<ul>';
        $prev = $parentLevel;

        foreach ($headings as $item) {
            $prev <= $item['level'] ?: $result .= str_repeat('</ul>', $prev - $item['level']);
            $prev >= $item['level'] ?: $result .= '<ul>';

            $result .= '<li><a href="#' . $item['id'] . '">' . $item['text'] . '</a></li>';

            $prev = $item['level'];
        }

        return $result . '</ul>';
    }

    public function parseMergeTags(Editor $editor): Editor
    {
        $editor->descendants(function (&$node): void {
            if ($node->type !== 'mergeTag') {
                return;
            }

            $map = $this->getMergeTagsMap();

            if (filled($map)) {
                $node->content = [
                    (object) [
                        'type' => 'text',
                        'text' => $map[$node->attrs->id] ?? null,
                    ],
                ];
            }
        });

        return $editor;
    }

    public function parseMentionItems(Editor $editor): Editor
    {
        $editor->descendants(function (&$node): void {
            if ($node->type !== 'mention') {
                return;
            }

            $node->content = [
                (object) [
                    'type' => 'text',
                    'text' => $node->attrs->label ?? $node->attrs->id,
                ],
            ];
        });

        return $editor;
    }

    public function getMergeTagsMap(): array
    {
        return $this->evaluate($this->mergeTagsMap) ?? [];
    }
}
