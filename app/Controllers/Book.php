<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Book extends Controller
{
    private function ambilLinkBaca($book)
    {
        if (!isset($book->formats)) {
            return '';
        }

        foreach ($book->formats as $type => $url) {
            if (strpos($type, 'text/html') !== false) {
                return $url;
            }
        }

        foreach ($book->formats as $type => $url) {
            if (strpos($type, 'text/plain') !== false) {
                return $url;
            }
        }

        return '';
    }

    public function index()
    {
        $keyword = $this->request->getGet('search') ?? '';

        if ($keyword == '') {
            $url = 'https://gutendex.com/books/?mime_type=text/html';
        } else {
            $url = 'https://gutendex.com/books/?mime_type=text/html&search=' . urlencode($keyword);
        }

        $data = [
            'books' => [],
            'keyword' => $keyword,
            'error' => null
        ];

        try {
            $json = @file_get_contents($url);

            if ($json === false) {
                $data['error'] = 'Data buku gagal dimuat.';
            } else {
                $result = json_decode($json);
                $data['books'] = $result->results ?? [];
            }

        } catch (\Exception $e) {
            $data['error'] = 'API sedang tidak dapat diakses.';
        }

        return view('books', $data);
    }

    public function detail($id)
    {
        $keyword = $this->request->getGet('search') ?? '';
        $url = 'https://gutendex.com/books/' . $id;

        $data = [
            'book' => null,
            'keyword' => $keyword,
            'error' => null
        ];

        try {
            $json = @file_get_contents($url);

            if ($json === false) {
                $data['error'] = 'Detail buku gagal dimuat.';
            } else {
                $data['book'] = json_decode($json);
            }

        } catch (\Exception $e) {
            $data['error'] = 'Detail buku gagal dimuat.';
        }

        return view('detail', $data);
    }

    public function read($id)
    {
        $keyword = $this->request->getGet('search') ?? '';
        $url = 'https://gutendex.com/books/' . $id;

        $data = [
            'book' => null,
            'keyword' => $keyword,
            'readUrl' => '',
            'error' => null
        ];

        try {
            $json = @file_get_contents($url);

            if ($json === false) {
                $data['error'] = 'Buku gagal dimuat.';
            } else {
                $book = json_decode($json);

                $data['book'] = $book;
                $data['readUrl'] = $this->ambilLinkBaca($book);
            }

        } catch (\Exception $e) {
            $data['error'] = 'Buku tidak dapat dibaca saat ini.';
        }

        return view('reader', $data);
    }
}