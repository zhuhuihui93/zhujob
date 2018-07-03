<?php

/*
 * This file is part of the Imagine package.
 *
 * (c) Bulat Shakirzyanov <mallluhuct@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Common\Plugin\Imagine\Filter\Basic;

use Common\Plugin\Imagine\Filter\FilterInterface;
use Common\Plugin\Imagine\Image\ImageInterface;

/**
 * A copy filter
 */
class Copy implements FilterInterface
{
    /**
     * {@inheritdoc}
     */
    public function apply(ImageInterface $image)
    {
        return $image->copy();
    }
}
