<?php

namespace EricLagarda\NovaEmbed\Invokes;

use Embed\Embed;
use Embed\Exceptions\InvalidUrlException;
use Illuminate\Http\Request;

class NovaEmbedData
{
    /**
     * @param Request $request
     */
    public function __invoke(Request $request, $url = null, $type = null, $cacheTime = null)
    {
        $url = $this->getTypeAndUrl($request, $url, $type);
        $type = $this->getTypeAndUrl($request, $url, $type, 'type');

        $time = $cacheTime ?? $this->getCache($request);

        if ($url && $this->checkValidUrl($url)) {
            $embed = cache()->remember(md5($url), $time, function () use ($url) {
                try {
                    return Embed::create($url);
                } catch (InvalidUrlException $e) {
                    return (object) ['code' => null, 'image' => null];
                }
            });

            return [
                'code'   => $embed->code,
                'image'  => $embed->image,
                'aspect' => $embed->aspectRatio,
            ];
        }

        return null;
    }

    /**
     * @param $request
     * @param $url
     * @param $type
     * @param $returnType
     * @return mixed
     */
    private function getTypeAndUrl($request, $url, $type, $returnType = 'url')
    {
        if ($returnType == 'url') {
            if ($url !== null) {
                return $url;
            }

            if ($request->has('url')) {
                return $request->get('url');
            }
        }

        if ($type !== null) {
            return $type;
        }

        if ($request->has('type')) {
            return $request->get('type');
        }
    }

    /**
     * @return mixed
     */
    private function getCache(Request $request)
    {
        if ($request->has('cache') && $request->get('cache') != null) {
            return $request->get('cache');
        }

        if (version_compare(app()->version(), '5.8', '>=')) {
            return 60 * 60 * 24 * 31;
        }

        return 60 * 24 * 31;
    }

    /**
     * @param $url
     */
    private function checkValidUrl(string $url): bool
    {
        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            return false;
        }

        return true;
    }
}
