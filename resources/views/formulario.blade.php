{{ $hola }}
{{ $id }}

<form class="" action="{{ route('post.product') }}" method="post" enctype="multipart/form-data">
  @csrf
  <input type="text" name="nombre" value="" placeholder="Nombre">
  <input type="number" name="precio" value="" placeholder="Precio">
  <input type="file" name="archivo" value="">
  <button type="submit" name="button">Subir</button>
</form>
