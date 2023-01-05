<?php

namespace App\Packages\Normalizer;

use App\Entity\Embeddable\ButtonSettings;
use App\Entity\Reference\Icon;
use App\Repository\Reference\IconRepository;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class IconNormalizer implements NormalizerInterface, DenormalizerInterface
{
    /** @var ObjectNormalizer */
    private $normalizer;

    /** @var IconRepository */
    private $iconRepository;

    /**
     * IconNormalizer constructor.
     * @param ObjectNormalizer $normalizer
     * @param IconRepository $iconRepository
     */
    public function __construct(ObjectNormalizer $normalizer, IconRepository $iconRepository)
    {
        $this->normalizer = $normalizer;
        $this->iconRepository = $iconRepository;
    }

    /**
     * @param ButtonSettings $object
     * @param null $format
     * @param array $context
     * @return array
     */
    public function normalize($object, $format = null, array $context = array()): array
    {
        $data = $this->normalizer->normalize($object, $format, $context);

        if ($iconId = $object->getIconId()) {
            $icon = $this->iconRepository->find($iconId);
            if ($icon) {
                $data['icon'] = [
                    'id' => $icon->getId(),
                    'name' => $icon->getName(),
                    'code' => $icon->getCode(),
                    'class' => $icon->getClass(),
                ];
            } else {
                $data['icon'] = null;
            }
        }

        return $data;
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof ButtonSettings;
    }

    public function denormalize($data, $class, $format = null, array $context = [])
    {
        $icon = null;

        if (isset($data['icon'])) {
            if (isset($data['icon']['id'])) {
                $findBy = 'id';
                $findValue = $data['icon']['id'];
            } elseif (isset($data['icon']['code'])) {
                $findBy = 'code';
                $findValue = $data['icon']['code'];
            }

            if (isset($findBy) && isset($findValue)) {
                $icon = $this->iconRepository->findOneBy([$findBy => $findValue]);
            }

            if (!$icon) {
                $icon = $this->normalizer->denormalize($data['icon'], Icon::class);
                $em = $this->iconRepository->createQueryBuilder('q')->getEntityManager();
                $em->persist($icon);
                $em->flush();
            }
            unset($data['icon']);
        }

        /** @var ButtonSettings $object */
        $object = $this->normalizer->denormalize($data, $class, $format, $context);

        if ($icon instanceof Icon) {
            $object->setIcon($icon);
            $object->setIconId($icon->getId());
        }

        return $object;
    }

    public function supportsDenormalization($data, $type, $format = null)
    {
        return $type === ButtonSettings::class && isset($data['icon']);
    }
}
