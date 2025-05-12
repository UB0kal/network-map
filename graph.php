<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Graph with Icons by Node Name</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="main.css">
    <script src="//unpkg.com/force-graph"></script>
</head>

<body>
    <div class="w-full h-screen bg-slate-900">
    <div id="graph"></div>

<script>
  function getCookieValue(name) {
    const cookiesString = document.cookie || "";
    const cookiesArray = cookiesString.split(';');
    const prefix = name + "=";

    for (let i = 0; i < cookiesArray.length; i++) {
      let cookie = cookiesArray[i];
      while (cookie.charAt(0) === ' ') {
        cookie = cookie.substring(1);
      }
      if (cookie.indexOf(prefix) === 0) {
        return cookie.substring(prefix.length, cookie.length);
      }
    }
    return null;
  }

  function getCookieAsJson(cookieName) {
    const cookieValue = getCookieValue(cookieName);

    if (cookieValue === null) {
      console.log(`Cookie "${cookieName}" not found.`);
      return null;
    }

    try {
      const decodedValue = decodeURIComponent(cookieValue);

      const jsonData = JSON.parse(decodedValue);
      return jsonData;

    } catch (error) {
      console.error(`Error parsing JSON from cookie "${cookieName}":`, error);
      console.error("Original cookie value:", cookieValue);
      return null;
    }
  }

  const dataArrayFromCookie = getCookieAsJson('devices');
  console.log(dataArrayFromCookie);

  const N = 50;
  const gData = {
    nodes: dataArrayFromCookie,
    links: []
      .filter(id => id)
      .map(id => ({
        source: id,
        target: Math.round(Math.random() * (id-1))
      }))
  };

  const Graph = new ForceGraph()
    (document.getElementById('graph'))
      .linkDirectionalParticles(2)
      .graphData(gData);
</script>

    </div>
</body>
</html>
