<?php

namespace Ninja\BanThis;

final class Censor
{
    public const DICTIONARY_PATH = __DIR__ . DIRECTORY_SEPARATOR . '../resources/dict' . DIRECTORY_SEPARATOR;

    private array $words = [];

    private string $replacer;

    private static ?array $checks = null;

    private Whitelist $whitelist;

    public function __construct(private readonly Dictionary $dictionary)
    {
        $this->whitelist = new Whitelist();
        $this->replacer = '*';
        $this->setDictionary($this->dictionary);
    }


    public function setDictionary(Dictionary $dictionary): self
    {
        $this->words = $dictionary->words();
        return $this;
    }

    public function addDictionary(Dictionary $dictionary): self
    {
        $this->words = array_merge($this->words, $dictionary->words());
        return $this;
    }

    public function addWords(array $words): self
    {
        $words = array_merge($this->words, $words);
        $this->words = array_keys(array_count_values($words));

        return $this;
    }

    public function whitelist(array $list): self
    {
        $this->whitelist->add($list);
        return $this;
    }

    public function setReplaceChar(string $replacer): self
    {
        $this->replacer = $replacer;

        return $this;
    }

    /**
     *  Generates a random string.
     *
     * @param string $chars Chars that can be used.
     * @param int $len   Length of the output string.
     *
     *
     * @return string
     */
    public function rand(string $chars, int $len): string
    {
        return str_shuffle(
            str_repeat($chars, (int)($len / strlen($chars))) .
            substr($chars, 0, $len % strlen($chars))
        );
    }

    private function generate(bool $fullWords = false): void
    {
        $badwords = $this->words;
        $replacements = $this->replacements();

        $censorChecks = array();
        for ($x = 0, $xMax = count($badwords); $x < $xMax; $x++) {
            $censorChecks[$x] = $fullWords
                ? '/\b' . str_ireplace(array_keys($replacements), array_values($replacements), $badwords[$x]) . '\b/i'
                : '/'   . str_ireplace(array_keys($replacements), array_values($replacements), $badwords[$x]) . '/i';
        }

        self::$checks = $censorChecks;
    }

    private function replacements(): array
    {
        return [
            'a' => '(a|a\.|a\-|4|@|Á|á|À|Â|à|Â|â|Ä|ä|Ã|ã|Å|å|α|Δ|Λ|λ)',
            'b' => '(b|b\.|b\-|8|\|3|ß|Β|β)',
            'c' => '(c|c\.|c\-|Ç|ç|¢|€|<|\(|{|©)',
            'd' => '(d|d\.|d\-|&part;|\|\)|Þ|��|Ð|ð)',
            'e' => '(e|e\.|e\-|3|€|È|è|É|é|Ê|ê|∑)',
            'f' => '(f|f\.|f\-|ƒ)',
            'g' => '(g|g\.|g\-|6|9)',
            'h' => '(h|h\.|h\-|Η)',
            'i' => '(i|i\.|i\-|!|\||\]\[|]|1|∫|Ì|Í|Î|Ï|ì|í|î|ï)',
            'j' => '(j|j\.|j\-)',
            'k' => '(k|k\.|k\-|Κ|κ)',
            'l' => '(l|1\.|l\-|!|\||\]\[|]|£|∫|Ì|Í|Î|Ï)',
            'm' => '(m|m\.|m\-)',
            'n' => '(n|n\.|n\-|η|Ν|Π)',
            'o' => '(o|o\.|o\-|0|Ο|ο|Φ|¤|°|ø)',
            'p' => '(p|p\.|p\-|ρ|Ρ|¶|þ)',
            'q' => '(q|q\.|q\-)',
            'r' => '(r|r\.|r\-|®)',
            's' => '(s|s\.|s\-|5|\$|§)',
            't' => '(t|t\.|t\-|Τ|τ|7)',
            'u' => '(u|u\.|u\-|υ|µ)',
            'v' => '(v|v\.|v\-|υ|ν)',
            'w' => '(w|w\.|w\-|ω|ψ|Ψ)',
            'x' => '(x|x\.|x\-|Χ|χ)',
            'y' => '(y|y\.|y\-|¥|γ|ÿ|ý|Ÿ|Ý)',
            'z' => '(z|z\.|z\-|Ζ)'
        ];
    }

    /**
     *  Apply censorship to $string, replacing $badwords with $censorChar.
     *
     * @param string $string    String to be censored.
     * @param bool $fullWords Option to censor by word only.
     *
     * @return array
     */
    public function clean(string $string, bool $fullWords = false): array
    {
        if (!self::$checks) {
            $this->generate($fullWords);
        }

        $newstring = [
            'orig' => html_entity_decode($string),
            'clean' => '',
            'matched' => []
        ];

        $original = $this->whitelist->replace($newstring['orig']);
        $counter = 0;

        $newstring['clean'] = preg_replace_callback(
            self::$checks,
            function ($matches) use (&$counter, &$newstring) {
                $newstring['matched'][$counter++] = $matches[0];
                return (strlen($this->replacer) === 1)
                    ? str_repeat($this->replacer, strlen($matches[0]))
                    : $this->rand($this->replacer, strlen($matches[0]));
            },
            $original
        );

        $newstring['clean'] = $this->whitelist->replace($newstring['clean'], true);
        return $newstring;
    }
}
