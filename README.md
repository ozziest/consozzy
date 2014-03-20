Consozzy
========

Consozzy, PHP tabanlı konsol uygulaması geliştirme kütüpahanesidir. Basit yönlendirme, çoklu dil desteği,
renkli kullanıcı mesajları gibi temel özellikleri içerisinde barındırır. Bunun haricinde 
sizin tarafınızdan geliştirilecek bölümler rahatlıkla uygulamaya dahil edilebilir. Bu kütüphane ile 
çok hızlı bir şekilde konsol uygulaması geliştirebilirsiniz.

***
### Kullanım
```bash
$ php console.php
```

***
### Çekirdek Komutlar


* `help`: Komut açıklamalarını gösterir.
* `clear`: Ekranı temizler.
* `history`: Son komutlar listelenir.
* `exit`: Konsoldan çıkar.
* `set:colors [argument]`: Kullanıcı mesajlarının renklerini ayarlar. (true, false)
* `set:messages [argument]`: Gösterilecek kullanıcı mesajı seviyesini belirler. 
	* 0 -> Hiç bir mesaj gösterilmez.
	* 1 -> Sadece başarı mesajları
	* 2 -> Başarı ve bilgi mesajları
	* 3 -> Başarı, bilgi ve uyarı mesajları.
* `set:errors [argument]`: Hataların gösterimini ayarlar. (true, false)

***
### Genişletme

Kendi yazacağınız kütüphaneler ile temel konsol uygulamasını genişletebilirsiniz.
Bunun için *Libraries* klasörü altında dosya adı aynı olmak şartıyla aşağıdaki 
şekilde bir class oluşturabilirsiniz. *(Libraries/Sample.php)*

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

Genişletme aşamasında kullanabileceğiniz çekirdek methodlar;

* `success([message])`: Ekrana yeşil renkte başarılı mesajı yazar. (-ln)
* `info([message])`: Ekrana mavi renkte bilgi mesajı yazar. (-ln)
* `warning([message])`: Ekrana sarı uyarı renkte mesajı yazar. (-ln)
* `error([message])`: Ekrana kırmızı renkte hata mesajı yazar. (-ln)
* `write([message, color])`: Ekrana mesaj yazar ve alt satıra geçmez.
* `writeln([message, color])`: Ekrana mesaj yazar ve alt satıra geçer.
* `ready()`: Kendi geliştirdiğiniz kütüphanelerde alt komutlar almak isteyebilirsiniz. 
Yukarıdaki örnekte kullanım şekli gösterilmiştir.

***
### Renkler

Aşağıdaki renkler varsayılan olarak konsol uygulaması içerisinde kullanılabilir. 
Dilerseniz bu renklerin değerlerini değiştirebilir ya da yeni renk tanımlaması 
yapabilirsiniz. Renk değerleri *System/Colors.php* dosyası içinde bulunmaktadır.

* `black`
* `dark_gray`
* `blue`
* `light_blue`
* `green`
* `light_green`
* `cyan`
* `light_cyan`
* `red`
* `light_red`
* `purple`
* `light_purple`
* `brown`
* `yellow`
* `light_gray`
* `white`

***
### Ayarlar ve Dil Değerleri

Konsol ile ilgili tüm ayarlar `System/Config.php` dosyasının içerisinde tanımlanmıştır.
Bu bölümdeki ayarları değiştirerek konsol çalışmasını konfigüre edebilirsiniz. 

Dil değerleri *Language* klasörü altında bulunmaktadır. `console.php` dil dosyası içerisinde
konsol çekirdeğinde kullanılan mesajları görebilirsiniz. Bu dosya varsayılan olarak konsol
açılışında yüklenmektedir. Dilerseniz bu dosyaya dil değeri ekleyebilirsiniz. Ayrıca konsol
çalışması anında siz de dil dosyası yüklenmesini isteyebilirsiniz. `Libraries/Sample.php` 
dosyası içerisinde aşağıda görüldüğü şekilde çalışma anında dil dosyası yüklenmesi işlemi
tanımlanmıştır.

```php
	// Warning message
	$this->warning('Language file loading...');
	// Language file loading...
	if (System\Language::load('sample')) {
		// Load operation is successfull!
		$this->success('Language file was loaded!');
		// Language value written.
		$this->info('lang:sample_key');
	}
```

***
### Ekran Görüntüsü

![alt text](http://www.ozguradem.net/wp-content/uploads/2014/03/console1.jpg "Ekran Görüntüsü")




