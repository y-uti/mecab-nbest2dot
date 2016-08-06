<?php

main($argv);

function main($argv)
{
    $nodes = [ '-1:BOS' => build_bos() ];
    $edges = [];
    $key = false;
    while ($line = fgets(STDIN)) {
        $key = process_line($line, $key, $nodes, $edges);
    }

    write_dot($nodes, $edges);
}

function process_line($line, $prevkey, &$nodes, &$edges)
{
    $line = trim($line);
    if (strlen($line) == 0) {
        return false;
    }

    $cols = explode("\t", $line);

    if ($prevkey === false) {
        $prevkey = '-1:BOS';
    }

    $key = "${cols[0]}:${cols[1]}";

    add_to_nodes($nodes, $key, $cols);
    add_to_edges($edges, $prevkey, $key, $cols[3]);

    return $key;
}

function add_to_nodes(&$nodes, $key, $cols)
{
    if (!array_key_exists($key, $nodes)) {
        $nodes[$key] = build_node($cols);
    }

    $cost = $cols[4];
    if ($cost < $nodes[$key]['cost']) {
        $nodes[$key]['cost'] = $cost;
    }
}

function build_bos()
{
    return build_node([ -1, 0, 0, 0, 0, 'BOS' ]);
}

function build_node($cols)
{
    return [
        'pos'      => $cols[0],
        'id'       => $cols[1],
        'wordcost' => $cols[2],
        'cost'     => $cols[4],
        'surface'  => $cols[5],
        'info'     => count($cols) > 6 ? $cols[6] : ''
    ];
}

function add_to_edges(&$edges, $from, $to, $cost)
{
    if (!array_key_exists($from, $edges)) {
        $edges[$from] = [];
    }
    $edges[$from][$to] = $cost;
}

function write_dot($nodes, $edges)
{
    echo "digraph {\n";
    write_dot_nodes($nodes);
    write_dot_edges($edges);
    echo "}\n";
}

function write_dot_nodes($nodes)
{
    foreach ($nodes as $key => $node) {
        $surface = $node['surface'];
        $info = $node['info'] != '' ? "[${node['info']}]" : '';
        $wordcost = $node['wordcost'];
        $cost = $node['cost'];
        echo "  \"$key\" [\n";
        echo "    label = \"$surface$info\n($wordcost/$cost)\"\n";
        echo "  ];\n";
    }
}

function write_dot_edges($edges)
{
    foreach ($edges as $from => $nodes) {
        foreach ($nodes as $to => $cost) {
            echo "  \"$from\" -> \"$to\" [ label = \"$cost\" ];\n";
        }
    }
}
