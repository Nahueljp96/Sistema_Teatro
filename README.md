<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel


#### NAHUEL::

RECORDAR: SETIE LA BASE DE DATOS PARA QUE ALMACENE ENTRADAS POR DEFAULT 1 PARA TEST, LUEGO HAY QUE MODIFICAR PARA QUE PUEDAN COMPRAR MAS DE UNA ENTRADA.

DEBUGGEAR QUE NO SE VEAN LAS IMAGENES EN PRODUCCIÓN: 

Si el directorio storage no existe, créalo manualmente para asegurarte de que Laravel pueda escribir en él:
mkdir -p /home/u594708880/domains/bisque-ram-716931.hostingersite.com/public_html/public/storage
(OBVIO USANDO EL SSH)
Comando para crear enlace simbolico (ya que artisan no deja si es webcompartido) :
ln -s /home/u594708880/domains/bisque-ram-716931.hostingersite.com/storage/app/public /home/u594708880/domains/bisque-ram-716931.hostingersite.com/public_html/public/storage

(recordar que para que la web pueda ver las imagenes, se deben almacenar en "public_html/public/"crear carpeta "storage" ) 
