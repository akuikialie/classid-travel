<?php

use App\Models\Spatie\Role;

if (!function_exists('menuSidebar')) {

    function menu(): array
    {
        $menu = [
            [
                'navheader' => 'Dashboard',
            ],
            [
                'url' => route('dashboard.admin'),
                'name' => 'Dashboard',
                'icon' => 'bx bxs-dashboard bx-tada',
                'show_in' => [
                    'roles' => [Role::RoleSA],
                    'permissions' => [],
                ],
            ],

            [
                'navheader' => 'Master',
            ],
            [
                'url' => null,
                'name' => 'Setup',
                'icon' => 'bx bx-cog bx-tada',
                'show_in' => [
                    'roles' => [Role::RoleSA],
                    'permissions' => [],
                ],
                "submenu" => [
                    [
                        "url" => null,
                        "name" => "Setup Paket",
                        "icon" => "bx bx-right-arrow-alt",
                    ], [
                        "url" => null,
                        "name" => "Setup Fasilitas",
                        "icon" => "bx bx-right-arrow-alt",
                    ], [
                        "url" => null,
                        "name" => "Setup Tujuan",
                        "icon" => "bx bx-right-arrow-alt",
                    ],[
                        "url" => null,
                        "name" => "Setup Keberangkatan",
                        "icon" => "bx bx-right-arrow-alt",
                    ],
                ]
            ],

        ];


        return $menu;
    }

    /**
     * description
     *
     * @param
     * @return
     */
    function menuSidebar(string $layout = 'vertical-menu'): array
    {

        if ($layout == 'vertical-menu') {
            $menu = menuVertical();
        } else {
            $menu = menuHorizontal();
        }

        $sidebar_menu = [];
        for ($i = 0; $i < count($menu); $i++) {
            if (!empty($menu[$i]['submenu']) && is_array($menu[$i]['submenu'])) {
                $submenu = [];
                foreach ($menu[$i]['submenu'] as $key => $value) {
                    $submenu[] = array_to_object($value);
                }
                $extra_submenu = [
                    'submenu' => $submenu,
                ];
                $value = array_replace($menu[$i], $extra_submenu);
                $extra_tag = [
                    'tag' => count($value['submenu']),
                    "tagcustom" => "badge badge-light-danger badge-pill badge-round float-right mr-2",
                ];
                $value = array_merge($value, $extra_tag);
                $sidebar_menu[] = array_to_object($value);
            } else {
                $sidebar_menu[] = array_to_object($menu[$i]);
            }
        }

        // dd($sidebar_menu);

        return $sidebar_menu;
    }


    function menuVertical(): array
    {
        $menu = menu();
        return $menu;
    }

    function menuHorizontal(): array
    {
        $menu = [];
        $navheader = null;
        foreach (collect(menu()) as $key => $value) {
            if (isset($value['navheader']) && !empty($value['navheader'])) {
                $navheader = $value['navheader'] . ':' . (isset($value['icon']) ? $value['icon'] : null);
                continue;
            }
            $menu[$navheader][] = $value;
        }

        $data = parseHorizontalMenus($menu);

        // dd(collect($data)->sort()->toArray());
        return $data;
    }

    function parseHorizontalMenus(array $menu): array
    {
        foreach ($menu as $key => $value) {
            if (count($value) == 1) {
                $single_menu = collect($value)->first();
                $format[] = $single_menu;
            } else {
                $separator = explode(':', $key);
                $format[] = [
                    'url' => null,
                    'name' => ucwords($separator[0]),
                    'icon' => $separator[1],
                    // 'prefix' => 'Roles & Permissions',
                    "submenu" => $value,
                ];
            }
        }
        // dd($format);
        return $format;
    }
}


if (!function_exists('getValueMatch')) {

    /**
     * Convert Array into Object in deep
     *
     * @param array $array
     * @return
     */

    function getValueMatch($array, $default, $multidimensional = false, $type = 'name', $multidimensional_type = 'submenu')
    {
        foreach (collect($array)->toArray()[$multidimensional_type] as $key => $row) {
            if ($multidimensional) {
                if (empty($row->$multidimensional_type) && !isset($row->$multidimensional_type)) {
                    if ($row->$type == $default) {
                        return true;
                    }
                } else {
                    if (multidimension($row, $type, $default, $multidimensional_type)) {
                        return true;
                    }
                }
            } else {
                if ($row->$type == $default) {
                    return true;
                }
            }
        }
        return false;
    }

    function multidimension($value, $type, $default, $menu)
    {
        if (!empty($value->$menu) && isset($value->$menu)) {
            foreach ($value->$menu as $key => $row) {
                if (!empty($row->$menu) && isset($row->$menu)) {
                    if ($row->$menu) {
                        if (multidimension($row, $type, $default, $menu)) {
                            return true;
                        }
                    }
                } else {
                    if ($row->$type == $default) {
                        return true;
                    }
                }
            }
        }

        return false;
    }
}


if (!function_exists('get_path_url')) {

    /**
     * Convert Array into Object in deep
     *
     * @param array $array
     * @return
     */
    function get_path_url($url): string
    {
        $url = $url ?: '#';
        $parts = parse_url(request()->url());
        $domain = $parts['host'] . (isset($parts['port']) ? (':' . $parts['port']) : '');
        $urlPath = explode((string)$domain, $url);
        return (string)collect($urlPath)->last();
    }
}
