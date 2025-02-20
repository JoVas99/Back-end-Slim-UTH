# Back-end Slim para prueba tecnica
Genera los endpoints de mi API REST para crear, actualizar, listar y eliminar estudiantes.

## Tecnoligias
- Slim
- PHP
- MySQL

## Instalación
1. Clona el siguiente repositorio:
```sh
git clone https://github.com/JoVas99/Back-end-Slim-UTH.git
```

2. Instala las dependencias:
```sh
composer install
```

## Dependencias 
- slim/slim
- slim/psr7
- firebase/php-jwt
- vlucas/phpditenv
- php-di/slim-bridge

## Tablas de la base de datos

Tabla estudiante: 

| id | nombre | apellido | edad | genero | usuario_id |
|----|--------|----------|------|--------|------------|

Tabla usuarios:

| id | correo | password | rol |
|----|--------|----------|-----|


## Implementacion en Railway
1. Registrase en Railway
2. Crear un nuevo proyecto, seleccionar GitHub y selecciona tu repositorio de Slim
3. Agregar la variables de entorno, si esta en Railway el MySQL mas facil aun por que se puede importar al Slim
4. Configurar servidor en Railway en este caso utilice Docker ya que trabaja con esa tecnologia Railway y lo detecto sin problemas.
