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
  // Random tree
  const N = 50;
  const gData = {
    nodes: [...Array(N).keys()].map(i => ({ id: i })),
    links: [...Array(N).keys()]
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
