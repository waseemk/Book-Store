<?php
namespace App\Libraries;

use App\Models\Book;
use App\Models\Author;
use App\Models\Genre;
use App\Models\Publisher;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class BookDataImportProcessor {
    
    /**
     * Import sale product records from the file into sale product Import Table with error related to each row (If Any)
     *
     * @param   string  $file_location   The location of the file on the local server
     * @return  mixed  Boolean TRUE if the file could be imported, array of formatting error msgs otherwise
     */
    public function importData() {

        try{
            
            $response = Http::get('https://fakerapi.it/api/v1/books?_quantity=200');
            if ($response->successful()) {
                $jsonResponseArray = $response->json();

                $jsonResponseData = (isset($jsonResponseArray['data']) && !empty($jsonResponseArray['data'])) ? $jsonResponseArray['data'] : array();

                foreach ($jsonResponseData as $data) {
                    $title          = isset($data['title']) ? $data['title'] : '';
                    $isbn           = isset($data['isbn']) ? $data['isbn'] : '';
                    $author         = isset($data['author']) ? $data['author'] : '';
                    $genre          = isset($data['genre']) ? $data['genre'] : '';
                    $description    = isset($data['description']) ? $data['description'] : '';
                    $publishedDate  = isset($data['published']) ? $data['published'] : '';
                    $publisher      = isset($data['publisher']) ? $data['publisher'] : '';

                    if(!empty($title) && !empty($isbn) && !empty($author) && !empty($genre) && !empty($description) && !empty($publishedDate) && !empty($publisher)) {

                        $savedAuthor = Author::where('name', $author)->first();
                        if(empty($savedAuthor)) {
                            $author_id = Author::create(['name' => $author, 'status' => 1])->id;
                        } else {
                            $author_id = $savedAuthor->id;
                        }

                        $savedGenre = Genre::where('name', $genre)->first();
                        if(empty($savedGenre)) {
                            $genre_id = Genre::create(['name' => $genre, 'status' => 1])->id;
                        } else {
                            $genre_id = $savedGenre->id;
                        }

                        $savedPublisher = Publisher::where('name', $publisher)->first();
                        if(empty($savedPublisher)) {
                            $publisher_id = Publisher::create(['name' => $publisher, 'status' => 1])->id;
                        } else {
                            $publisher_id = $savedPublisher->id;
                        }

                        $isbnExist = Book::where('isbn', $isbn)->count();

                        if(!$isbnExist) {
                            $published = Carbon::parse($publishedDate)->format('Y-m-d');
                            $book = Book::create(
                                [
                                    'title'         => $title,
                                    'isbn'          => $isbn,
                                    'author_id'     => $author_id,
                                    'genre_id'      => $genre_id,
                                    'publisher_id'  => $publisher_id,
                                    'description'   => $description,
                                    'image'         => null,
                                    'published'     => $published,
                                    'status'        => 1,
                                ]
                            );
                        }
                    }
                    
                }
            }

            return true;
            
        }catch(Exception $e){
		    \Log::error('BookDataImportProcessor::handle - Error during execution: '.$e->getMessage());
        }
        return false;
    }
}
