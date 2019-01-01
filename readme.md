<p align="center"><img src="https://laravel.com/assets/img/components/logo-laravel.svg"></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/license.svg" alt="License"></a>
</p>

## Proje Çalıştırma
<p>
    Projeyi <b>GIT</b> ile çektikten sonra, projenizin dosya dizinine gelerek terminalden <br><br>
    <code>composer update</code><br><br>
    <code>php artisan key:generate</code><br><br>
    komutlarını çalıştırın.
</p>
<p>
    Proje şu anda çalışır durumda olacaktır.
</p>

## İşlemler

<p>
Proje içerisinde iki adet controller oluşturulmuştur.<br>
    <ul>
        <li>
            <code>php artisan make:controller controllerIsmi<code>
        </li>
        <li>
            <b>HomeController</b>
            <p>
                <pre>
                    public function index()
                    {
                        return view('home');
                    }
                </pre>
            </p>
            <p>
                Burada sadece anasayfada hangi view geleceğini belirttik.
            </p>
        </li>
        <li>
            <b>Lang Controller</b><br>
            <pre>
                public function index($lang)
                {
                    $langs= ['tr', 'en'];
                    if (in_array($lang, $langs))
                    {
                        Session::put('lang', $lang);
                        return Redirect::back()->with('msj', 'Dil Değiştirildi');
                    }
                }
            </pre>
    <p>
        Seçilebilecek dil dosyalarının isimlerini <b>langs</b> dizisinde tutuyoruz. URL'den gelecek olan <code>$lang</code> yani dil ismini bu dizi içerisinde var mı yok mu kontrol ettirerek var ise <code>Session</code> ile <b>lang</b> üzerinde tutuyoruz. Dil değişiklidi şeklinde bir alert verilebilecek şekilde geri gönderiyoruz.
    </p>
        </li>
    </ul>
</p>

### Middleware Oluşturma

<pre>
php artisan make:middleware Language
</pre>
<p>Komutu ile <b>Language Middleware</b> oluşturuyoruz. </p>
<h5>Language Middleware İçi</h5>
<pre>
public function handle($request, Closure $next)
    {
        if ($lang= Session::get('lang'))
        {
            Lang::setLocale($lang);
        }
        return $next($request);
    }
    </pre>
<p>
    Bir önceki adımda <b>Session</b> da tutmuş olduğumuz <b>lang</b> değerimizi burada çağırıyoruz. Eğer varsa <b>$lang</b> değişkenine atıyoruz. <code>Lang::setLocale($lang)</code fonksiyonu ile de yeni dilimizi set ediyoruz. <br>
    </p>
 
 ### Route Oluşturma Web.php
 
 <b>Birçok şekilde kullanımı olan root bölümünde karar bize kalmış oluyor. Şöyleki ister her bir get işleminin sonuna o gette çalışmasını istediğimiz middleware i verebiliriz. İstersek tüm requestlerde çalışacak şekilde middleware oluşturabiliriz istersek de belirlemiş olduğumuz gruba özel middleware oluşturabiliriz.</b> 
 <pre>
 Route::group(['middleware' => 'dil'],function (){
    Route::GET('/', 'HomeController@index');
    Route::GET('/lang/{lang}', 'LangController@index');
});
 </pre>
<p>Yukarıki kodumuzda <b>dil</b> olarak oluşturduğum middleware altında gruplama işlemiyaptım. </p>

### Kernel.php Middleware i Aktif Etme
<pre>
 protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
        'api' => [
            'throttle:60,1',
            'bindings',
        ],
        // bu alanı biz ekledik
        //oluşturduğumuz Language Middlewaremizin yolunu gösteriyoruz. Routeta belirttiğimiz <b>dil</b> buradaki isimlendirme oluyor.
        'dil' => [
            \App\Http\Middleware\Language::class
        ],
        
        // bu alanı biz ekledik
        //Eğer yukarıdaki web alanına yolu göstermiş olsaydık tüm requestlerde çalışmasını sağlamış olacaktık. 
        //Elbette hatırlatmakta fayda var. 
        <br>
        //Laravel 5.6 ve sonrasında bu şekilde oluyor daha önceki sürümlerde tüm requestlerde geçerli olmasını istiyorsak <b>middleware</b> dizisinin içerisine ekleme yapmalıydık.
    ];
</pre>

### Özel Bir Route a Middleware Verme
<pre>
protected <b>$routeMiddleware</b> = [
        'auth' => \Illuminate\Auth\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'yeniDil' => \App\Http\Middleware\Language::class,
    ];
    </pre>
<p><b>routeMiddleware</b> inin son satırına yapmış olduğumuz ekleme ile oluşturduğumuz yeniDil middlewarein yolunu gösterdik. <b>YeniDil isimli middleware i şimdi sadece hangi URL'de kullanmak istiyorsak o Route a ekleme yapacağız.
    </p>
    <pre>
    Route::GET('/', 'HomeController@index')->middleware('yeniDil');
    </pre>
    <br>
    <br>    
### Umarım yararlı bir anlatım olmuştur. İyi kodlamalar dilerim.
    
