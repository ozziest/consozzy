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
namespace Ozziest\Consozzy\Libraries;
use Ozziest\Consozzy\System as System;

class Sample extends System\Kernel
{

	public function index($params)
	{	
		// Sample user message
		$this->success('sample:test command was successfully executed.');
		$this->warning('sample:test command was successfully executed.');
		$this->info('sample:test command was successfully executed.');
		$this->error('sample:test command was successfully executed.');
		// Listening sub command
		$command = $this->ready();
		// Write sample sub command
		$this->warning("New command: $command ");

		if (method_exists($this, $command)) {
			$this->{$command}();
		} else {
			$this->error('Sub command not found');
		}

	}

	private function sub()
	{
		$this->warning('This is simple sub process');
	}

}
```

Yukarıdaki örnekteki gibi bir sınıf tanımladığınızda, bu sınıfı konsol üzerinden
aşağıdaki kod aracılığı ile çağırabilirsiniz.

```bash
-> sample:index myParam1 myParam2 myParam3
```

Oluşturduğunuz methoda dilediğiniz kadar parametre gönderebilirsiniz. Eğer sadece 1
adet parametre gönderirseniz o parametre string olarak, bir den fazla parametre 
gönderirseniz gönderdiğiniz parametre dizi olarak methoda ulaşacaktır. 

Genişletme aşamasında kullanabileceğiniz Kernel methodları;

* `success`: Ekrana yeşil renkte başarılı mesajı yazar. (-ln)
* `info`: Ekrana mavi renkte bilgi mesajı yazar. (-ln)
* `warning`: Ekrana sarı uyarı renkte mesajı yazar. (-ln)
* `error`: Ekrana kırmızı renkte hata mesajı yazar. (-ln)
* `write`: Ekrana mesaj yazar ve alt satıra geçmez.
* `writeln`: Ekrana mesaj yazar ve alt satıra geçer.
* `ready`: Kendi geliştirdiğiniz kütüphanelerde alt komutlar almak isteyebilirsiniz. 
Yukarıdaki örnekte nasıl kullanacağınız gösterilmiştir.


***
### Ayarlar ve Dil Değerleri

Konsol ile ilgili tüm ayarlar `system/config.php` dosyasının içerisinde tanımlanmıştır.
Bu bölümdeki ayarları değiştirerek konsol çalışmasını konfigüre edebilirsiniz.

Dil değerleri *language* klasörü altında bulunmaktadır. `lang:key` değeri ile yazdırmak 
istediğiniz değeri belirttiğinizde `key` ile belirtilen dil anahtarı seçilen dil
değerinde tanımlı olmak zorundadır. 

***
### Ekran Görüntüsü

![alt text](http://www.ozguradem.net/wp-content/uploads/2014/03/console.jpg "Ekran Görüntüsü")




