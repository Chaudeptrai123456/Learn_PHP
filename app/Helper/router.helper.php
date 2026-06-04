<?php
use App\Core\Helper;

function buildFilterUrl($category, $brand, $price, $search = '', $sort = 'default', $page = null) {

    $category = $category ?: 'all';
    $brand    = $brand ?: 'all';
    $price    = $price ?: 'all';

    // đổi lại cho đúng URL
    $price = str_replace('_', '-', $price);

    if ($category === 'all' && $brand === 'all' && $price === 'all') {
        $url = "/assignment/categories";
    } else {
        $url = "/assignment/categories/$category/$brand/$price";
    }

    $query = [];

    if (!empty($search)) {
        $query['search'] = $search;
    }

    if (!empty($sort) && $sort !== 'default') {
        $query['sort'] = $sort;
    }

    if (!empty($page) && $page > 1) {
        $query['page'] = $page;
    }

    if (!empty($query)) {
        $url .= '?' . http_build_query($query);
    }

    return $url;
}
?>