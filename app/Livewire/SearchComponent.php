<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Component;

class SearchComponent extends Component
{
    public string $search = "";
    //    TODO: add bundle and posts to search

    public string $title;

    public function mount()
    {
        if (!$this->search) {
            $this->title = __("store.POPULAR CATEGORIES");
        }
    }

    #[Computed]
    public function popular(): Collection
    {
        //        if ($this->search) {
        //            return collect();
        //        }

        return Category::select(["id", "name", "slug"])
            ->where("name->en", "!=", "HYDRATE")
            ->where("name->en", "!=", "TREAT")
            ->product()
            ->get();
    }

    #[Computed]
    public function products(): Collection
    {
        if (!$this->search) {
            return collect();
        }
        return Product::search($this->search)->get();
    }

    public function render()
    {
        return view("livewire.search-component");
    }
}
