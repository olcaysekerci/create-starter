<?php

namespace Modules\Settings\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Modules\Settings\Services\SettingsService;

class SettingsController extends Controller
{
    protected $settingsService;

    public function __construct(SettingsService $settingsService)
    {
        $this->settingsService = $settingsService;
    }

    /**
     * Ayarlar ana sayfası - Mail ayarlarına yönlendir
     */
    public function index()
    {
        return redirect()->route('admin.settings.mail.index');
    }

    /**
     * Mail ayarları sayfası
     */
    public function mail()
    {
        $mailSettings = $this->settingsService->getMailSettings();

        return Inertia::render('Admin/MailSettings', [
            'mailSettings' => $mailSettings,
        ]);
    }

    /**
     * Mail ayarlarını güncelle
     */
    public function updateMail(Request $request)
    {
        $request->validate([
            'driver' => 'required|string|in:smtp,mailpit,log,array',
            'host' => 'required|string|max:255',
            'port' => 'required|integer|min:1|max:65535',
            'username' => 'nullable|string|max:255',
            'password' => 'nullable|string|max:255',
            'encryption' => 'nullable|string|in:tls,ssl,null',
            'from_address' => 'required|email|max:255',
            'from_name' => 'required|string|max:255',
        ]);

        $settings = $request->only([
            'driver', 'host', 'port', 'username', 'password', 
            'encryption', 'from_address', 'from_name'
        ]);

        $success = $this->settingsService->updateMailSettings($settings);

        if ($success) {
            return back()->with('success', 'Mail ayarları başarıyla güncellendi.');
        } else {
            return back()->with('error', 'Mail ayarları güncellenirken hata oluştu.');
        }
    }

    /**
     * Test mail gönder
     */
    public function testMail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $email = $request->email;

        try {
            $success = $this->settingsService->testMailSettings($email);

            if ($success) {
                return back()->with('success', 'Test mail başarıyla gönderildi!');
            } else {
                return back()->with('error', 'Test mail gönderilemedi. Mail ayarlarını kontrol edin.');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Test mail gönderilirken hata oluştu: ' . $e->getMessage());
        }
    }

    /**
     * Uygulama ayarları sayfası
     */
    public function app()
    {
        $appSettings = $this->settingsService->getAppSettings();

        return Inertia::render('Admin/AppSettings', [
            'appSettings' => $appSettings,
        ]);
    }

    /**
     * Uygulama ayarlarını güncelle
     */
    public function updateApp(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'required|url|max:255',
            'timezone' => 'required|string|max:100',
            'locale' => 'required|string|max:10',
        ]);

        $settings = $request->only(['name', 'url', 'timezone', 'locale']);

        $success = $this->settingsService->updateAppSettings($settings);

        if ($success) {
            return back()->with('success', 'Uygulama ayarları başarıyla güncellendi.');
        } else {
            return back()->with('error', 'Uygulama ayarları güncellenirken hata oluştu.');
        }
    }

    /**
     * Sistem ayarları sayfası
     */
    public function system()
    {
        $systemSettings = $this->settingsService->getSystemSettings();

        return Inertia::render('Admin/SystemSettings', [
            'systemSettings' => $systemSettings,
        ]);
    }

    /**
     * Sistem ayarlarını güncelle
     */
    public function updateSystem(Request $request)
    {
        $request->validate([
            'debug_mode' => 'boolean',
            'log_level' => 'required|string|in:emergency,alert,critical,error,warning,notice,info,debug',
        ]);

        $settings = $request->only(['debug_mode', 'log_level']);

        $success = $this->settingsService->updateSystemSettings($settings);

        if ($success) {
            return back()->with('success', 'Sistem ayarları başarıyla güncellendi.');
        } else {
            return back()->with('error', 'Sistem ayarları güncellenirken hata oluştu.');
        }
    }
} 