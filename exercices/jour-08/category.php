<?php

class Category
{
    public function __construct(
        private int $id,
        private string $name,
        private string $description
    ) {
    }

    public function getSlug(): string
    {
        return strtolower(str_replace(' ', '-', $this->name));
    }
}

$category1 = new Category(1, 'a a AAAAAAAAAAAAAA  aaa aaa  a a aaCategory 1', 'Category 1 description');
$category2 = new Category(2, 'Category 2', 'Category 2 description');
$category3 = new Category(3, 'Category 3', 'Category 3 description');

echo '<h1>'.$category1->getSlug().'</h1>';
echo '<h1>'.$category2->getSlug().'</h1>';
echo '<h1>'.$category3->getSlug().'</h1>';
