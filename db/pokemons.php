<?php
  /*
  https://pokefanaticos.com/pokedex/
  https://pokefanaticos.com/pokedex/pokedex-listar/
  https://pokefanaticos.com/pokedex/pokedex_funciones.php?accion=pokemonlistadonumerico
  */
  $pokemons = json_decode(
    file_get_contents("pokemons.json"),
    true
  );
  $db = new PDO('sqlite:pokemons.db');
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $db->beginTransaction();
  try {
    foreach ($pokemons as &$pokemon) {
      $sql = "INSERT INTO pokemons (number, name, type_1, type_2, weight, height, img)
        VALUES (?,?,?,?,?,?,?)";
      $db->prepare($sql)->execute([
        $pokemon['numero'],
        strtoupper($pokemon['nombre']),
        strtoupper($pokemon['tipo_1']),
        strtoupper($pokemon['tipo_2']),
        $pokemon['peso'],
        $pokemon['altura'],
        str_replace(' ', '',
          'https://pokefanaticos.com/pokedex/images/pokemon_iconos/' .
          $pokemon['numero'].' .png'
        ),
      ]);
    }
    $db->commit();
  } catch (Exception $e) {
    $db->rollBack();
  }
?>
