Consozzy
========

Consozzy, PHP tabanlı konsol uygulaması geliştirme aracıdır. Basit yönlendirme, dil desteği,
renkli kullanıcı mesajları gibi temel özellikleri içerisinde barındırır. Bunun haricinde 
sizin tarafınızdan geliştirilecek bölümler rahatlıklar ayrı bir kütüphane olarak uygulamaya
dahil edilebilir.

***
### Kullanım
```bash
$ php console.php
```

***
### Çekirdek Komutlar


* `help`: Komut açıklamalarını gösterir.
* `exit`: Konsoldan çıkar.
* `clear`: Ekranı temizler.
* `set:color [param]`: Kullanıcı mesajlarının renklerini ayarlar. (true, false)
* `set:message [param]`: Gösterilecek kullanıcı mesajı seviyesini belirler. 
				 0. Hiç bir mesaj gösterilmez.
				 1. Sadece başarı mesajları
				 2. Başarı ve bilgi mesajları
				 3. Başarı, bilgi ve uyarı mesajları.
* `set:error [param]`: Hataların gösterimini ayarlar. (true, false)

***
### Genişletme

Kendi yazacağınız kütüphaneler ile temel konsol uygulamasını genişletebilirsiniz.
Bunun için *libraries* klasörü altında dosya adı aynı olmak şartıyla aşağıdaki 
şekilde bir class oluşturabilirsiniz. *(libraries/command.php)*

```php
class Command extends Kernel
{

	public function test($param)
	{
		// Simple messages
		$this->success('Simple success message.');
		$this->warning('Simple error message');
		$this->info('Simple information message');
		// Language message
		$this->error('lang:langCode');

	}

}
```

Yukarıdaki örnekteki gibi bir sınıf tanımladığınızda, bu sınıfı konsol üzerinden
aşağıdaki kod aracılığı ile çağırabilirsiniz.

```bash
-> command:test myParam1 myParam2 myParam3
```

Oluşturduğunuz methoda dilediğiniz kadar parametre gönderebilirsiniz. Eğer sadece 1
adet parametre gönderirseniz o parametre string olarak, bir den fazla parametre 
gönderirseniz gönderdiğiniz parametre dizi olarak methoda ulaşacaktır. 

***
### Ayarlar ve Dil Değerleri

Konsol ile ilgili tüm ayarlar `system/config.php` dosyasının içerisinde tanımlanmıştır.
Bu bölümdeki ayarları değiştirerek konsol çalışmasını konfigüre edebilirsiniz.

Dil değerleri *language* klasörü altında bulunmaktadır. `lang:key` değeri ile yazdırmak 
istediğiniz değeri belirttiğinizde `key` ile belirtilen dil anahtarı seçilen dil
değerinde tanımlı olmak zorundadır. 





