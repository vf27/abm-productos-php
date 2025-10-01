CREATE DATABASE productos_mercadolibre;

USE productos_mercadolibre;

CREATE TABLE productos (
  id_producto INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  titulo VARCHAR(150) NOT NULL,
  descripcion TEXT NOT NULL,
  precio DECIMAL(10,2) NOT NULL,
  stock INT(11) NOT NULL,
  categoria VARCHAR(100) NOT NULL,
  publicado BOOLEAN DEFAULT 1
);

INSERT INTO productos (titulo, descripcion, precio, stock, categoria, publicado) VALUES
('Perchero De Puerta Horizontal 5 Ganchos Colgante Metálico', 'Perchero colgante metálico horizontal para puerta.', 14154.21, 140, 'Hogar, Muebles y Jardín', 1),
('Portaespiral / Portasahumerio Contra Mosquitos Metal', 'Portaespirales / portasahumerios metálico de excelente calidad y estética, en chapa de 1,2 mm pintado al horno.', 11357.01, 300, 'Hogar, Patio y Jardín', 1),
('Reloj Pared Metálico 40 Cm Moderno Números Contrapuestos', 'Reloj metálico realizado en chapa 1,2 mm con pintura al horno negra. Materiales que le brindan elegancia y calidad. Agujas doradas. Diseño calado sin fondo que se adapta a cualquier decoración o agregado de luz. Modelo de 40 cm.', 42810.87, 20, 'Joyas y Relojes', 1),
('Cartel Letras Y Números Domiciliarios Acero Negro 50x25cm', 'Distinguí el frente de tu hogar con nuestras placas domicilio de alta estética y calidad con diseños modernos exclusivos.', 38402.52, 1, 'Hogar, Patio y Jardín', 1),
('Portacelular Metálico, Apoya Tablet/Celular, Centro De Carga', 'Apoya celular metálico diseñado para carga segura o lectura. Por su robustez y firmeza brinda un lugar seguro para apoyar celulares o tablets. Realizado en chapa de 1,2 mm. Pintura al horno.', 7797.51, 400, 'Souvenirs, Cotillón y Fiestas', 1),
('Portamaceta Symple Hojas Metálico 40x20x20 Cm Deco Calado', 'Portamaceta/fogonero vertical en metal calado modelo líneas. Medidas: 40x20x20 cm.', 54471.51, 10, 'Hogar, Muebles y Jardín', 1),
('Portarollo Servilletero Decorativo Calado Metálico', 'Portarrollo servilletero metálico de 1,2 mm, pintura al horno no tóxica. Modelo guardas pampa.', 11036.60, 190, 'Hogar, Muebles y Jardín', 1),
('Bandeja Decorativa Metálica Negra 35x22 Cm Chica', 'Fabricada en metal, su resistencia y durabilidad la convierten en una opción práctica para el uso diario.', 12631.45, 20, 'Hogar, Muebles y Jardín', 1),
('Servilletero Doble Metálico Diseño Decorativo', 'Servilletero doble en metal 1,2 mm con pintura al horno epoxi calada en corte láser.', 9122.31, 40, 'Hogar, Muebles y Jardín', 1),
('Fogonero/Macetero Metálico Deco C/Quemador Sin Piedras 20x20', 'Fogonero metálico con quemador. Medidas fogón 20x20x20 cm con base separada y manijas.', 45653.31, 35, 'Hogar, Patio y Jardín', 1);