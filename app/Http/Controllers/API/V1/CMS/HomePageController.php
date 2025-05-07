<?php

namespace App\Http\Controllers\API\V1\CMS;

use App\Enums\Page;
use App\Enums\Section;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\CMS;
use App\Models\DynamicPage;
use App\Models\SystemSetting;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HomePageController extends Controller
{

    /**
     * Fetches the home page banner details from the CMS.
     *
     * This method retrieves the banner information for the home page, including
     * the ID, title, subtitle, and image associated with the banner. If no data
     * is found, it returns a response indicating that no data is available.
     *
     * @param Request $request The incoming request instance.
     * @return \Illuminate\Http\JsonResponse A JSON response containing the banner data or an error message.
     */

    public function getHomeBanner(Request $request): JsonResponse
    {
        try {
            $data = CMS::where('page', Page::HomePage)->where('section', Section::Banner)->select('id', 'title', 'sub_title', 'image')->first();
            if (!$data) {
                return Helper::jsonResponse(true, 'No data found', 200, []);
            }
            $data = [
                'id' => $data->id,
                'title' => $data->title,
                'button_text' => $data->sub_title,
                'video' => $data->image
            ];
            return Helper::jsonResponse(true, 'Banner data fatced successfully', 200, $data);
        } catch (Exception $e) {
            Log::error("HomePageController::getHomeBanner" . $e->getMessage());
            return Helper::jsonErrorResponse('Something went wrong', 500);
        }
    }

    /**
     * Retrieves active social links for the home page.
     *
     * This method fetches and paginates the social links data associated with
     * the home page's social link container. It includes the ID, title, image,
     * and link URL of each social link. If no 'per_page' parameter is specified
     * in the request, a default of 25 items per page is used.
     *
     * @param Request $request The incoming request instance.
     * @return \Illuminate\Http\JsonResponse A JSON response containing the social links data or an error message.
     */

    public function getSocialLinks(Request $request): JsonResponse
    {
        try {
            $per_page = $request->has('per_page') ? $request->per_page : 25;
            $data = CMS::select('id', 'title', 'image', 'link_url')->where('page', Page::HomePage)->where('section', Section::SocialLinkContainer)->where('status', 'active')->paginate($per_page);
            return Helper::jsonResponse(true, 'Social links data fatced successfully', 200, $data, true);
        } catch (Exception $e) {
            Log::error("HomePageController::getSocialLinks" . $e->getMessage());
            return Helper::jsonErrorResponse('Something went wrong', 500);
        }
    }

    /**
     * Retrieves the system information, which includes the system name, logo, favicon, copyright text,
     * description, contact number, address, and email.
     *
     * @param Request $request The incoming request instance.
     * @return \Illuminate\Http\JsonResponse A JSON response containing the system information or an error message.
     */
    public function getSystemInfo(Request $request): JsonResponse
    {
        try {
            $data = SystemSetting::select('id', 'system_name', 'logo', 'favicon', 'copyright_text', 'description', 'contact_number', 'address', 'email')->first();
            return Helper::jsonResponse(true, 'System data fetched successfully', 200, $data);
        } catch (Exception $e) {
            Log::error("HomePageController::getSystemInfo" . $e->getMessage());
            return Helper::jsonErrorResponse('Failed to retrieve System', 403);
        }
    }


    /**
     * Retrieves a paginated list of active dynamic pages.
     *
     * This method fetches dynamic pages that are marked as active, including
     * their ID, title, and slug. If the request contains a 'per_page' parameter,
     * it uses that value for pagination; otherwise, it defaults to 25 items per
     * page. Returns a JSON response with the paginated data or an error message
     * in case of failure.
     *
     * @param Request $request The incoming request instance.
     * @return \Illuminate\Http\JsonResponse A JSON response with the dynamic pages data or an error message.
     */

    public function getDynamicPages(Request $request): JsonResponse
    {
        try {
            // Get 'per_page' from the request or default to 25
            $per_page = $request->has('per_page') ? $request->per_page : 25;
            $dynamicPages = DynamicPage::where('status', 'active')->select('id', 'page_title', 'page_slug')->paginate($per_page);
            if (!$dynamicPages) {
                return Helper::jsonResponse(true, 'No data found', 200, []);
            }
            return Helper::jsonResponse(true, 'Dynamic pages retrieved successfully.', 200, $dynamicPages, true);
        } catch (Exception $e) {
            return Helper::jsonErrorResponse('Failed to retrieve Dynamic pages data.', 403);
        }
    }
    /**
     * Retrieves a dynamic page by its slug.
     *
     * This method fetches a dynamic page based on its slug, including the ID,
     * title, slug, content, and status. If the page is not found or is not
     * active, it returns a JSON response with an error message and a status
     * code of 403.
     *
     * @param string $page_slug The slug of the dynamic page to retrieve.
     * @return \Illuminate\Http\JsonResponse A JSON response with the dynamic
     * page data or an error message.
     */
    public function showDaynamicPage($page_slug): JsonResponse
    {
        try {
            $page = DynamicPage::where('page_slug', $page_slug)->where('status', 'active')->firstOrFail();
            if (!$page) {
                return Helper::jsonResponse(true, 'No data found', 200, []);
            }
            return Helper::jsonResponse(true, 'Dynamic page retrieved successfully.', 200, $page);
        } catch (Exception $e) {
            return Helper::jsonErrorResponse('Failed to retrieve Dynamic page data.', 403);
        }
    }

    public function getCookieText(Request $request): JsonResponse
    {
        try {
            $data = CMS::where('page', Page::HomePage)->where('section', Section::CookieText)
                ->select('id', 'description')
                ->first();
            // if (!$data) {
            //     return Helper::jsonResponse(true, 'No data found', 200, []);
            // }
            $data = [
                'id' => $data->id ?? 1,
                'description' => $data->description ?? 'This website uses cookies to enhance your experience. By continuing to browse, you accept our use of cookies.',
            ];
            return Helper::jsonResponse(true, 'Cookie text retrieved successfully.', 200, $data);
        } catch (Exception $e) {
            Log::error("API::HomePageController::getCookieText" . $e->getMessage());
            return Helper::jsonErrorResponse('Failed to retrieve Cookie text', 403);
        }
    }
}
