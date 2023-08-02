<?php

function hasPermission($url, $method = 'get')
{
    $roles = getRoleCache(authUser());
    $permissions = [];

    if (!count($roles) < 0) {
        $roles = authUser()->role;
    }

    $method = strtolower($method);
    $splittedUrl = explode('/'.getSystemPrefix(), $url);
    if (count($splittedUrl) > 1) {
        $url = $splittedUrl[1];
    } else {
        $url = $splittedUrl[0];
    }

    foreach ($roles as $role) {       
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
        if ($check) {
            return true;
        }

        $permissions = array_merge($permissions, $role->permissions);
    }

    if ($permissions == null) {
        return false;
    }

    foreach ($permissions as $piurl) {
        if (\Str::is($piurl['url'], $url) && $piurl['method'] == $method) {
            $check = true;
        }
    }
    if ($check) {
        return true;
    }

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
                if ($check == true) {
                    break;
                }
            }
        } catch (\Exception $e) {
            return false;
        }
    }

    return $check;
}

function isPermissionSelected($permission, $permissions)
{
    $permission = json_decode($permission);
    $permissions = json_decode($permissions);
    $check = false;
    if (!is_array($permission)) {
        if ($permissions != null) {
            $exists = in_array($permission, $permissions);
            if ($exists) {
                $check = true;
            }
        }
    } else {
        $temCheck = false;
        if ($permissions != null) {
            foreach ($permission as $perm) {
                $exists = in_array($perm, $permissions);
                if ($exists) {
                    $temCheck = true;
                }
            }
        }
        $check = $temCheck;
    }

    return $check;
}
