<?php

use Illuminate\Support\Facades\Auth;

    function hasPermission($url, $method = 'get')
    {
        $role = session()->get('role');
        $method = strtolower($method);
        $splittedUrl = explode('/' . PREFIX, $url);
        if (count($splittedUrl) > 1) {
            $url = $splittedUrl[1];
        } else {
            $url = $splittedUrl[0];
        }
        if ($role->id == 1) {
            $permissionDeniedToSuperUserRoutes = Config::get('cmsConfig.permissionDeniedToSuperUserRoutes');
            $checkDeniedRoute = true;
            foreach ($permissionDeniedToSuperUserRoutes as $route) {
                if (\Str::is($route['url'], $url) && $route['method'] == $method) {
                    $checkDeniedRoute = false;
                }
            }
            return $checkDeniedRoute;
        }

        $permissionIgnoredUrls = Config::get('cmsConfig.permissionGrantedbyDefaultRoutes');

        $check = false;

        foreach ($permissionIgnoredUrls as $piurl) {
            if (\Str::is($piurl['url'], $url) && $piurl['method'] == $method) {
                $check = true;
            }
        }
        if ($check) return true;

        $permissions = $role->permissions;

        if ($permissions == null) {
            return false;
        }


        foreach ($permissions as $piurl) {
            if (\Str::is($piurl['url'], $url) && $piurl['method'] == $method) {
                $check = true;
            }
        }
        if ($check) return true;
        return false;

    }

    function hasPermissionOnModule($module)
    {
        $check = false;
        if (!$module['hasSubmodules']) {
            $check = hasPermission($module['route']);
        } else {
            try {
                foreach ($module['submodules'] as $submodule) {
                    $check = hasPermission($submodule['route']);
                    if($check == true){
                    break;
                    }
                }
            } catch (\Exception $e) {
                return false;
            }
        }
        return $check;
    }
