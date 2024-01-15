<?php

namespace AmbExpress\ViewModels;

use AmbExpress\dto\DTO_Search;
use AmbExpress\ViewModels\bulletin\BullArticleViewModel;
use Timber\Pagination;
use Timber\Post;
use Timber\PostQuery;
use Timber\Timber;

class SearchViewModel
{
    public $Type = 1; // magazine
    public $Years;
    public $Items;
    public $Articles;
    public $Sections;
    public $Mode;
    public $Total;
    public $Pagination;
    public $Selected = [];
    public $InSearch = false;

    private function LoadMagazines()
{
    $this->Years = ArticleViewModel::GetArticleDistinctYears();
    array_unshift($this->Years, 'Все');

    $meta_year = [];

    if ((isset($this->Selected['year'])) && ($this->Selected['year'] !== 'Все')) {
        $args = [
            'post_type' => 'magazine',
            'numberposts' => 30,
            'meta_query' => [
                [
                    'key' => 'release_date_from',
                    'value' => $this->Selected['year'],
                    'compare' => 'LIKE',
                ],
            ],
            'orderby' => ['ID' => 'DESC'],
        ];
        $this->Items = Timber::get_posts($args);
        $meta_year = [
            'key' => 'art_date',
            "value" => $this->Selected['year'],
            "compare" => 'LIKE',
        ];
        wp_reset_postdata();
    } else {
        $this->Items = (new MagazinesViewModel())->GetAllMagazines();
    }

    $meta_mag = [];

    if (isset($this->Selected['mag_num'])) {
        global $wpdb;

        $magIds = $wpdb->get_results("select p.post_id from wp_postmeta p where p.meta_key = 'art_links' and p.meta_value like '%{$this->Selected['mag_num']}%'");
        $meta_mag = [];
        if (count($magIds) > 0) {
            foreach ($magIds as $mag_id) {
                $meta_mag[] = $mag_id->post_id;
            }
        }
    }

        $meta_free = [];
        if (isset($this->Selected['onlyFree'])) {
            $meta_free = [
                'key' => 'art_only_for_registered',
                "value" => '1',
                "compare" => "NOT LIKE",
            ];
        }

        $tax_section = '';
        if (isset($this->Selected['section'])) {
            if ($this->Selected['section'] !== 'Все') {
                $tax_section = [
                    'taxonomy' => 'section',
                    'field' => 'id',
                    'terms' => $this->Selected['section'],
                    'operator' => 'IN'
                ];
            }
        }

        $additionalParam = [
            'relation' => 'AND',
            $meta_year,
            $meta_free
        ];

        global $paged;
        $args = [
            'post_type' => ['article', 'news'],
            'post_status' => 'publish',
            'posts_per_page' => 30,
            's' => $this->Selected['search_text'],
            'post__in' => $meta_mag,
            'tax_query' => [$tax_section],
            'paged' => $paged,
            'meta_query' => $additionalParam,
            'orderby' => 'post_date',
        ];

        if ($this->InSearch) {
            $articleCollection = new PostQuery($args);

            $this->Articles = $articleCollection->get_posts();
            $this->Total = $articleCollection->found_posts;

            foreach ($this->Articles as $article) {
                if (isset($article->custom['art_links']))
                    $mag_id = $article->custom['art_links'][0];
                else
                    $mag_id = '';

                $magazine = new Post($mag_id);
                $article->custom['magazine'] = $magazine;
            }
            $this->Pagination = $articleCollection->pagination();
        } else {
            $this->Articles = [];
            $this->Total = 0;
        }
    }

    private function LoadBulletins()
    {
        $meta_year = [];

        if ((isset($this->Selected['year'])) && ($this->Selected['year'] !== 'Все')) {
            $args = [
                'post_type' => 'bulletin',
                'numberposts' => 30,
                'meta_query' => [
                    [
                        "key" => "bull_release_date_from",
                        "value" => $this->Selected['year'],
                        "compare" => "LIKE",
                    ]
                ],
                'orderby' => ['ID' => 'DESC'],
            ];
            $this->Items = Timber::get_posts($args);
            $meta_year = [
                'key' => 'bart_date',
                "value" => $this->Selected['year'],
                "compare" => 'LIKE',
            ];
            wp_reset_postdata();
        } else {
            $args = [
                'post_type' => 'bulletin',
                'numberposts' => -1,
                'meta_query' => [
                    [
                        "key" => "bull_common_number",
                        "value" => 30,
                        "compare" => ">",
                        'type' => 'NUMERIC'
                    ]
                ],
                'orderby' => ['ID' => 'DESC'],
            ];

            $this->Items = (new BulletinViewModel())->GetAllBulletins();
        }

        $meta_mag = [];
        if (isset($this->Selected['mag_num'])) {
            global $wpdb;

            $magIds = $wpdb->get_results("select p.post_id from wp_postmeta p where p.meta_key = 'bart_links' and p.meta_value like '%{$this->Selected['mag_num']}%'");
            $meta_mag = [];
            if (count($magIds) > 0) {
                foreach ($magIds as $mag_id) {
                    $meta_mag[] = $mag_id->post_id;
                }
            }
        }

        $meta_free = [];
        if (isset($this->Selected['onlyFree'])) {
            $meta_free = [
                'key' => 'bart_only_for_registered',
                "value" => '1',
                "compare" => "NOT LIKE",
            ];
        }

        $tax_section = '';
        if (isset($this->Selected['section'])) {
            if ($this->Selected['section'] !== 'Все') {
                $tax_section = [
                    'taxonomy' => 'sectionbull',
                    'field' => 'id',
                    'terms' => $this->Selected['section'],
                    'operator' => 'IN'
                ];
            }
        }

        $additionalParam = [
            'relation' => 'AND',
            $meta_year,
            $meta_free
        ];

        global $paged;
        $args = [
            'post_type' => ['bulletinsarticle'],
            'post_status' => 'publish',
            'posts_per_page' => 30,
            's' => $this->Selected['search_text'],
            'post__in' => $meta_mag,
            'tax_query' => [$tax_section],
            'paged' => $paged,
            'meta_query' => $additionalParam,
            'orderby' => 'post_date',
        ];

        if (isset($this->Selected['year']) || isset($this->Selected['search_text']) || isset($this->Selected['section'])) {
            $articleCollection = new PostQuery($args);

            $this->Articles = $articleCollection->get_posts();
            $this->Total = $articleCollection->found_posts;

            foreach ($this->Articles as $article) {
                if (isset($article->custom['bart_links']))
                    $mag_id = $article->custom['bart_links'][0];
                else
                    $mag_id = '';

                $magazine = new Post($mag_id);
                $article->custom['magazine'] = $magazine;
            }
            $this->Pagination = $articleCollection->pagination();
        } else {
            $this->Articles = [];
            $this->Total = 0;
        }
    }

    private function LoadBoth()
    {
        $meta_year = [];

        if ((isset($this->Selected['year'])) && ($this->Selected['year'] !== 'Все')) {
            $meta_year = [
                'key' => ['bart_date', 'art_date'],
                "value" => $this->Selected['year'],
                "compare" => 'LIKE',
            ];
        }

        $meta_mag = [];

        $meta_free = [];
        if (isset($this->Selected['onlyFree'])) {
            $meta_free = [
                'key' => ['bart_only_for_registered', 'art_only_for_registered'],
                "value" => '1',
                "compare" => "NOT LIKE",
            ];
        }

        $additionalParam = [
            'relation' => 'AND',
            $meta_year,
            $meta_free
        ];

        global $paged;
        $args = [
            'post_type' => ['bulletinsarticle', 'article', 'news'],
            'post_status' => 'publish',
            'posts_per_page' => 30,
            's' => $this->Selected['search_text'],
            'post__in' => $meta_mag,
            'paged' => $paged,
            'meta_query' => $additionalParam,
            'orderby' => 'post_date',
        ];

        if (isset($this->Selected['year']) || isset($this->Selected['search_text']) || isset($this->Selected['section'])) {
            $articleCollection = new PostQuery($args);

            $this->Articles = $articleCollection->get_posts();
            $this->Total = $articleCollection->found_posts;

            foreach ($this->Articles as $article) {
                if (isset($article->custom['bart_links']))
                    $mag_id = $article->custom['bart_links'][0];
                else if (isset($article->custom['art_links']))
                    $mag_id = $article->custom['art_links'][0];
                else
                    $mag_id = '';

                $magazine = new Post($mag_id);
                $article->custom['magazine'] = $magazine;
            }
            $this->Pagination = $articleCollection->pagination();
        } else {
            $this->Articles = [];
            $this->Total = 0;
        }
    }

    public function LoadData(DTO_Search $dto)
    {
        $this->Type = $dto->Type;
        $this->ItemNum = $dto->ItemNum;
        $this->Mode = $dto->Mode;
		
		
		$originalSearchText = $this->Selected['search_text'];

		// Обработка запроса пользователя: удаление окончаний
		$this->Selected['search_text'] = $this->removeEndings($this->Selected['search_text']);

        if ($this->Type == 1) // magazines
        {
            $this->LoadMagazines();
            $this->LoadSections();
        }
        else if ($this->Type == 2)
        {
            $this->LoadBulletins();
            $this->LoadSections();
        }
        else{
            $this->LoadBoth();
        }

        $this->LoadYears();
		
		$this->Selected['search_text'] = $originalSearchText;
    }

    private function LoadYears()
    {
        global $wpdb;

        if($this->Type == 1)
        {
            $years = BullArticleViewModel::GetArticleDistinctYears();
        }
        else if($this->Type == 2)
        {
            $years = BullArticleViewModel::GetBulletinsDistinctYears();
        }
        else {
            $years = BullArticleViewModel::GetBothDistinctYears();
        }

       $this->Years = $years;
        array_unshift($this->Years, "Все");
    }

    private function LoadSections()
    {
        if($this->Type == 1)
            $sectName = 'section';
        else
            $sectName = 'sectionbull';

        $sectVm = new SectionsViewModel($sectName);
        $sectVm->LoadSections();
        $this->Sections = $sectVm->Sections;
    }
	
	private function removeEndings($text)
{
    // Окончания, которые нужно удалить
    $endingsToRemove = [
        'ая', 'ее', 'ей', 'ие', 'ий', 'им', 'ом', 'ым', 'ой', 'ый', 'ые', 'ое', 'ие', 'ия', 'ье',
        'ам', 'ем', 'им', 'ом', 'ум', 'ям', 'ях', 'ах', 'ов', 'ах', 'ев', 'ов', 'иях', 'иям', 'иями',
        'ями', 'ях', 'ей', 'ий', 'ям', 'ем', 'ей', 'ях', 'еми', 'ями', 'ой', 'ый', 'ах', 'ях', 'ем',
        'ом', 'им', 'ым', 'ей', 'ов', 'ев', 'ов', 'ам', 'ям', 'ам', 'иях', 'иям', 'иями', 'ями', 'ье',
        'и', 'а', 'о', 'у', 'ы', 'е', 'ю', 'я'
    ];

    $words = explode(' ', $text);

    foreach ($words as &$word) {
        foreach ($endingsToRemove as $ending) {
            if (substr($word, -strlen($ending)) === $ending) {
                $word = substr($word, 0, -strlen($ending));
                break;
            }
        }
    }

    $processedText = implode(' ', $words);

    return $processedText;
}
}