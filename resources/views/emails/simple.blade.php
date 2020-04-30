<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

</script>
  </head>
  <body>
    <main>

      <section id="content">
            <p>UConnect</p>
            <p>You have requested a password reset. Insert this code:</p>
            <p>{{$code}}</p>
            <p>If it was not you, don't proceed and ignore this email.</p>
      </section>
    </main>
  </body>
</html>