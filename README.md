# Banka Mevduat Hesaplama Sistemi
![image](https://github.com/user-attachments/assets/3ed16476-df5f-4065-8568-c89761c13b53)
![image](https://github.com/user-attachments/assets/7ec3628b-9add-463c-82c0-530d2d2482ed)

## Proje Özeti
Bu proje, kullanıcıların farklı bankalardaki mevduat faiz oranlarını karşılaştırarak, yatıracakları miktara göre net getiri hesaplamalarına olanak tanır.

## Teknolojiler
- Laravel 11
- MySQL
- Postman (API testleri için)
- Sentry (Log yönetimi için)

## Kurulum
**Gereksinimler:**
- PHP 8.0 veya üzeri
- Composer
- MySQL
- Docker ve Docker-compose

### Docker ve Docker Compose Kurulumu

1. Docker ve Docker Compose'un sisteminizde kurulu olduğundan emin olun. Eğer kurulu değilse, [Docker'ı](https://docs.docker.com/get-docker/) ve [Docker Compose'u](https://docs.docker.com/compose/install/) kurmak için ilgili dokümanları takip edin.

2. Docker ve Docker Compose kurulumunu tamamladıktan sonra terminal üzerinden projeyi klonlayın:

   ```bash
   git clone https://github.com/kullanıcı_adı/proje_adı.git
   cd proje_adı```

2. **Proje Kurulumu:**
   ```bash
   git clone <repository-url>
   cd <repository-directory>
   composer install
   cp .env.example .env
   php artisan key:generate
   ```

3. **Veritabanı Ayarları:**
 
 `.env` dosyasını düzenleyerek veritabanı bağlantı bilgilerinizi girin.

4. **Proje Kurulumu:**

    *Docker konteynerlerini oluşturmak ve başlatmak için şu komutu çalıştırın:*

   ```bash
   docker-compose up -d
   ```
     *Veritabanı için gerekli migration işlemlerini yapın:*

   ```bash
   docker exec -it laravel_app bash
   php artisan optimize:clear
   php artisan migrate:fresh --seed
   ```

## API Kullanımı

### Rotalar

1. **Tüm Bankaları Listele**
   - **Yöntem:** `GET`
   - **URL:** `/api/banks`
   - **Açıklama:** Tüm bankaların listesini döner.
   - **Örnek Cevap:**
     ```json
     {
         "banks": [
             {
                 "id": 1,
                 "name": "Bank A",
                  "tax": "0.15",
                 "created_at": "2024-10-14T00:30:28.000000Z",
                 "updated_at": "2024-10-14T00:30:28.000000Z"
             },
             {
                 "id": 2,
                 "name": "Bank B",
                 "tax": "0.16",
                 "created_at": "2024-10-14T00:30:28.000000Z",
                 "updated_at": "2024-10-14T00:30:28.000000Z"
             },
             {
                 "id": 3,
                 "name": "Bank C",
                 "tax": "0.12",
                 "created_at": "2024-10-14T00:30:28.000000Z",
                 "updated_at": "2024-10-14T00:30:28.000000Z"
             }
         ]
     }
     ```

2. **Mevduat Oranlarını Getir**
   - **Yöntem:** `GET`
   - **URL:** `/api/deposit-rates`
   - **Açıklama:** Mevduat hesaplaması için gerekli döviz türleri ve vade sürelerini döner.
   - **Örnek Cevap:**
     ```json
     {
         "currencies": ["TRY", "USD", "EUR"],
         "durations": [30, 90, 180]
     }
     ```

3. **Faiz Hesaplama**
   - **Yöntem:** `POST`
   - **URL:** `/api/calculate-interest`
   - **Parametreler:**
     - `amount`: Yatırılacak miktar (örn: 10000)
     - `duration`: Vade süresi (örn: 30)
     - `currency`: Para birimi (örn: "TRY")
   - **Açıklama:** Verilen miktar, vade ve para birimine göre faiz hesaplaması yapar.
   - **Örnek İstek:**
     ```http
     POST /api/calculate-interest?amount=10000&duration=30&currency=TRY
     ```
   - **Örnek Cevap:**
     ```json
     [
         {
             "bank_name": "Bank A",
             "on_duration": "30 Gün",
             "gross_interest": "71.92 TL",
             "tax": "10.79 TL",
             "rate": "10.50 %",
             "net_interest": "61.13 TL",
             "final_balance": "10,061.13 TL"
         },
         {
             "bank_name": "Bank B",
             "on_duration": "30 Gün",
             "gross_interest": "65.07 TL",
             "tax": "9.76 TL",
             "rate": "9.50 %",
             "net_interest": "55.31 TL",
             "final_balance": "10,055.31 TL"
         },
         {
             "bank_name": "Bank C",
             "on_duration": "30 Gün",
             "gross_interest": "75.34 TL",
             "tax": "11.30 TL",
             "rate": "11.00 %",
             "net_interest": "64.04 TL",
             "final_balance": "10,064.04 TL"
         }
     ]
     ```



## Log Yönetimi

Bu projede log yönetimi için Sentry kullanılmıştır. Sentry, uygulamanızda oluşan hataları ve logları merkezi bir yerde toplamanıza olanak tanır. Ayrıca, uygulama performansını izleyerek hata ayıklama süreçlerinizi kolaylaştırır. Proje yapılandırmasında Sentry ile ilgili ayarları `.env` dosyasına ekleyebilirsiniz.

Log kayıtları, Sentry üzerinden izlenebilir. Geliştiriciler, hataların detaylarını Sentry panelinden görüntüleyebilir, bildirim alabilir ve analiz edebilir.


```bash
php artisan sentry:publish --dsn=https://your.sentry.io/number
```
It creates the config file (config/sentry.php) and adds the DSN to your .env file where you can add further configuration options:
```bash
SENTRY_LARAVEL_DSN=https://your.sentry.io/number
```

## Test

API uç noktalarını test etmek için Postman veya benzeri bir araç kullanabilirsiniz. Projenin API uç noktalarını test etmek için aşağıdaki adımları izleyebilirsiniz:


    1. Postman'ı açın.
    2. İlgili API uç noktasını seçin (GET, POST vb.).
    3. Gerekli parametreleri ekleyin.
    4. İsteği gönderin ve yanıtları görüntüleyin.

### Ek Bilgiler
- Proje, Larave framework'ü üzerinde geliştirilmiştir.
- Docker ve Docker Compose kullanılarak kolayca yönetilebilir.
- Veritabanı olarak MySQL kullanılmaktadır ve varsayılan bağlantı bilgileri `.env` dosyasında tanımlanmıştır.
