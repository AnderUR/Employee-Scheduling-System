<!DOCTYPE html>

<html>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>

<body>
  <table align="center" style="border: 2px solid lightgray;" width="500px">
    <tr>
      <td>
        <table cellpadding="10">
          <tr>
            <td>
              <!--Host the below image or another on a live server and link to it here -->
              <img src="<?=$titleImgSrc?>" alt="Logo" height="100%" width="100%" style="display: block;" />
            </td>
            <td>
              <h2 style="margin: 0;"><?= $title ?></h2>
            </td>
          </tr>
        </table>
      </td>
    </tr>

    <tr align="center">
      <td>
        <table cellpadding="20">
          <tr>
            <td style="font-size: 20px;">
              <p style="color: black;"><?= $emailBody ?></p>
            </td>
          </tr>
        </table>
      </td>
    </tr>

    <tr>
      <td>
        <table width="100%" bgColor="#2E697A" cellpadding="10" style="color: white; margin-top: 10px;">
          <tr align="center">
            <td>
              CONTACT US about account questions or concerns
            </td>
          </tr>
          <tr align="center">
            <td><?= $helpContact ?></td>
      </td>
    </tr>
  </table>
  </td>
  </tr>

  </table>
</body>

</html>