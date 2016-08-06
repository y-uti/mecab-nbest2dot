# mecab-nbest2dot

## Usage

```
$ echo 'すもももももももものうち' | ./mecab-nbest-prob.sh -N 10 | php nbest2dot.php | dot -Tpng -o lattice.png
```
