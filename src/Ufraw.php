<?php
namespace Astrotomic\Ufraw;

use Symfony\Component\Process\ProcessBuilder;

/**
 * Class Ufraw
 * @link http://www.linuxcertif.com/man/1/ufraw-batch/
 * general:
 * @method Ufraw silent() --silent
 * image manipulation:
 * @method Ufraw wb(string $value) --wb=camera|auto
 * @method Ufraw temperature(integer $value) --temperature=TEMP
 * @method Ufraw green(float $value) --green=GREEN
 * @method Ufraw gamma(float $value) --gamma=GAMMA
 * @method Ufraw linearity(float $value) --linearity=LINEARITY
 * @method Ufraw exposure(string|float $value) --exposure=auto|EXPOSURE
 * @method Ufraw restore(string $value) --restore=clip|lch|hsv
 * @method Ufraw clip(string $value) --clip=digital|film
 * @method Ufraw saturation(float $value) --saturation=SAT
 * @method Ufraw waveletDenoisingThreshold(float $value) --wavelet-denoising-threshold=THRESHOLD
 * @method Ufraw hotpixelSensitivity(float $value) --hotpixel-sensitivity=VALUE
 * @method Ufraw baseCurve(string $value) --base-curve=manual|linear|custom|camera|CURVE
 * @method Ufraw baseCurveFile(string $value) --base-curve-file=<curve-file>
 * @method Ufraw curve(string $value) --curve=manual|linear|CURVE
 * @method Ufraw curveFile(string $value) --curve-file=<curve-file>
 * @method Ufraw blackPoint(float $value) --black-point=auto|BLACK
 * @method Ufraw interpolation(string $value) --interpolation=ahd|vng|four-color|ppg|bilinear
 * @method Ufraw colorSmoothing() --color-smoothing
 * @method Ufraw grayscale(string $value) --grayscale=none|lightness|luminance|value|mixer
 * @method Ufraw grayscaleMixer(string $value) --grayscale-mixer=RED,GREEN,BLUE
 * @method Ufraw darkframe(string $value) --darkframe=FILE
 * output:
 * @method Ufraw shrink(float $value) --shrink=FACTOR
 * @method Ufraw size(integer $value) --size=SIZE
 * @method Ufraw rotate(string|integer $value) --rotate=camera|ANGLE|no
 * @method Ufraw crop(integer $value) --crop=PIXELS
 * @method Ufraw cropLeft(integer $value) --crop-left=PIXELS
 * @method Ufraw cropRight(integer $value) --crop-right=PIXELS
 * @method Ufraw cropTop(integer $value) --crop-top=PIXELS
 * @method Ufraw cropBottom(integer $value) --crop-bottom=PIXELS
 * @method Ufraw autoCrop() --auto-crop
 * @method Ufraw aspectRatio(string $value) --aspect-ratio X:Y
 * @method Ufraw lensfun(string $value) --lensfun=none|auto
 * @method Ufraw outType(string $value) --out-type=ppm|tiff|tif|png|jpeg|jpg|fits
 * @method Ufraw outDepth(integer $value) --out-depth=8|16
 * @method Ufraw compression(integer $value) --compression=VALUE
 * @method Ufraw exif() --exif
 * @method Ufraw noexif() --noexif
 * @method Ufraw zip() --zip
 * @method Ufraw nozip() --nozip
 * @method Ufraw outPath(string $value) --out-path=PATH
 * @method Ufraw output(string $value) --output=FILE
 * @method Ufraw overwrite() --overwrite
 * @method Ufraw embeddedImage() --embedded-image
 */
class Ufraw
{
    protected $bin = '/usr/bin/ufraw-batch';
    protected $arguments = [];
    protected $input = null;

    public function __construct($bin = null)
    {
        $this->bin($bin);
    }

    public function input($input)
    {
        if(!empty($input)) {
            $this->input = $input;
        }
        return $this;
    }

    public function argument($key, $value = null)
    {
        if(!empty($key)) {
            $this->arguments[$key] = '--'.$key.(!empty($value) ? '='.$value : '');
        }
        return $this;
    }

    public function bin($bin)
    {
        if(!empty($bin)) {
            $this->bin = $bin;
        }
        return $this;
    }

    public function run()
    {
        $process = $this->getProcess();
        $process->run();
        return $process;
    }

    protected function getProcess()
    {
        $builder = new ProcessBuilder($this->arguments);
        $builder->setPrefix($this->bin);
        $builder->add($this->input);
        return $builder->getProcess();
    }

    public function __call($name, $arguments)
    {
        $key = strtolower(preg_replace('/([A-Z])/', '-$1', $name));
        $value = array_key_exists(0, $arguments) ? $arguments[0] : null;
        $this->argument($key, $value);
        return $this;
    }
}