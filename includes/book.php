<?php

class SupaPress_Book {
	private $properties;
	private $result;
	private $book;

	public function __construct($result, $properties) {
		$this->properties = $properties;
		$this->result = $result;
	}

	public function has_books() {
		return $this->result->search !== null && count($this->result->search) > 0;
	}

	public function no_books() {
        echo (string) get_option('no_books') !== '' ? get_option('no_books') : SUPAPRESS_DEFAULT_NO_BOOKS_MESSAGE;
	}

	public function the_book() {
		$this->book = array_shift($this->result->search);
	}

	public function the_cover() {
		if( !empty( $this->book->image ) ) {
			echo "<img src='{$this->book->image}' alt='{$this->book->title}' />";
		}
	}

	public function the_title() {
		$title = '';

		if(isset($this->properties['show_title']) && $this->properties['show_title'] === 'on') {
			if( !empty( $this->book->title ) ) {
				$title = "<p>{$this->book->title}</p>";
			}
		}

		echo $title;
	}

	public function the_price() {
		$price = '';

		if(isset($this->properties['show_price']) && $this->properties['show_price'] === 'on') {
			if( !empty( $this->book->prices ) ) {
				foreach ( $this->book->prices as $p ) {
					if ( $p->locale === 'USD' ) {
						$price = "<p>&#36;{$p->amount}</p>";
						break;
					}
				}
			}
		}

		echo $price;
	}

	public function the_format() {
		$format = '';

		if(isset($this->properties['show_format']) && $this->properties['show_format'] === 'on') {
			if( !empty( $this->book->formats ) ) {
				foreach ( $this->book->formats as $f ) {
					if ( $f->isbn->isbn === $this->book->isbn13 ) {
						$format = "<p>{$f->format->name}</p>";
						break;
					}
				}
			}
		}

		echo $format;
	}

	public function the_author() {
		$authors = '';
		$separator = ', ';

		if(isset($this->properties['show_author']) && $this->properties['show_author'] === 'on' && count( $this->book->contributors ) > 0) {
			if( !empty( $this->book->contributors ) ) {
				foreach ( $this->book->contributors as $contributor ) {
					if ( trim( $contributor->contributor->name !== '' ) ) {
						$authors .= $contributor->contributor->name . $separator;
					}
				}

				$authors = '<p>' . trim( rtrim( $authors, $separator ) ) . '</p>';
			}
		}

		echo $authors;
	}

	public function the_publication_date() {
		$date = '';

		if(isset($this->properties['show_pubdate']) && $this->properties['show_pubdate'] === 'on') {
			if( !empty( $this->book->date ) ) {
				$date = "<p>{$this->book->date->date}</p>";
			}
		}

		echo $date;
	}

	public function the_summary() {
		$summary = '';

		if(isset($this->properties['show_summary']) && $this->properties['show_summary'] === 'on') {
			if( !empty( $this->book->summary ) ) {
				$summary = "<p>{$this->book->summary}</p>";
			}
		}

		echo $summary;
	}
}