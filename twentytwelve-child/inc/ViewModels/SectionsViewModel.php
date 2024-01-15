<?php


namespace AmbExpress\ViewModels;


class SectionsViewModel
{


	public $Sections = [];
    public $sectionName = '';

    /**
     * @param array $Sections
     */
    public function __construct(string $sectionName = 'section')
    {
        $this->sectionName = $sectionName;
    }

    public function LoadSections()
	{
		$sections = get_terms( $this->sectionName, [ 'hide_empty' => false ] );

        if(!empty($sections))
		    $this->sort_terms_hierarchically( $sections, $this->Sections );
        else
            $this->Sections = $sections;
	}

	/**
	 * Recursively sort an array of taxonomy terms hierarchically. Child categories will be
	 * placed under a 'children' member of their parent term.
	 *
	 * @param Array $cats taxonomy term objects to sort
	 * @param Array $into result array to put them in
	 * @param integer $parentId the current parent ID to put them in
	 */
	private function sort_terms_hierarchically( &$cats, &$into, $parentId = 0 )
	{
		if ( ! is_array( $cats ) )
		{
			return;
		}

		foreach ( $cats as $i => $cat )
		{
			if ( $cat->parent == $parentId )
			{
				$into[ $cat->term_id ] = $cat;
				unset( $cats[ $i ] );
			}
		}

		foreach ( $into as $topCat )
		{
			$topCat->children = array();
			$this->sort_terms_hierarchically( $cats, $topCat->children, $topCat->term_id );
		}
	}

	public function LoadSubSections( int $term_id )
	{
		$this->Sections = get_terms( [ 'taxonomy' => $this->sectionName, 'parent' => $term_id, 'hide_empty' => false ] );
	}
}