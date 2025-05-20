<?php

namespace App\Data;

use Illuminate\Support\Carbon;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Transformers\DateTimeInterfaceTransformer;

#[MapOutputName(SnakeCaseMapper::class)]
class PlumberComputeData extends Data
{

    /**
     * CMP: Crop Management Practice code or identifier.
     */
    #[MapOutputName('CMP')]
    public float $currentMaizePerformance;

    #[MapOutputName('FCY')]
    public int $currentFieldYield;

    #[MapOutputName('FR')]
    public bool $fertilizerRec;
    #[MapOutputName('IC')]
    public bool $interCropRec;
    #[MapOutputName('PP')]
    public bool $plantingPracticesRec;
    #[MapOutputName('SPP')]
    public bool $scheduledPlantingRec;
    #[MapOutputName('SPH')]
    public bool $scheduledHarvestRec;

    #[MapOutputName('PD')]
    #[WithTransformer(DateTimeInterfaceTransformer::class, format: 'Y-m-d')]
    public Carbon $plantingDate;
    #[MapOutputName('PD_window')]
    public int $plantingDateWindow;

    #[MapOutputName('HD')]
    #[WithTransformer(DateTimeInterfaceTransformer::class, format: 'Y-m-d')]
    public Carbon $harvestDate;
    #[MapOutputName('HD_window')]
    public int $harvestDateWindow;

    #[MapOutputName('area')]
    public float $fieldSize;
    #[MapOutputName('areaUnits')]
    public string $areaUnit;

    #[MapOutputName('cassPD')]
    public string $cassavaProduceType;
    #[MapOutputName('cassUP')]
    public float $cassavaUnitPrice;
    #[MapOutputName('cassUP_m1')]
    public float $cassUpM1;
    #[MapOutputName('cassUP_m2')]
    public float $cassUpM2;
    #[MapOutputName('cassUP_p1')]
    public float $cassUpP1;
    #[MapOutputName('cassUP_p2')]
    public float $cassUpP2;
    #[MapOutputName('cassUW')]
    public float $cassavaUnitWeight;

    #[MapOutputName('cost_LMO_areaBasis')]
    public string $costLmoAreaBasis;
    public float $costManualHarrowing;
    public float $costManualPloughing;
    public float $costManualRidging;
    public float $costTractorHarrowing;
    public float $costTractorPloughing;
    public float $costTractorRidging;

    #[MapOutputName('cost_weeding1')]
    public float $costWeedingOne;
    #[MapOutputName('cost_weeding2')]
    public float $costWeedingTwo;

    #[MapOutputName('country')]
    public string $countryCode;
    #[MapOutputName('email')]
    public bool $sendEmail;
    #[MapOutputName('SMS')]
    public bool $sendSms;

    #[MapOutputName('fallowGreen')]
    public bool $fallowGreen;
    #[MapOutputName('fallowHeight')]
    public int $fallowHeight;
    #[MapOutputName('fallowType')]
    public string $fallowType;

    #[MapOutputName('harrowing')]
    public bool $harrowing;
    #[MapOutputName('intercrop')]
    public string $interCroppedCrop;


    #[MapOutputName('lat')]
    public float $mapLat;
    #[MapOutputName('lon')]
    public float $mapLong;

    #[MapOutputName('maizePD')]
    public string $maizeProduceType;
    #[MapOutputName('maizeUP')]
    public float $maizeUnitPrice;
    #[MapOutputName('maizeUW')]
    public float $maizeUnitWeight;

    #[MapOutputName('sweetPotatoPD')]
    public string $sweetPotatoProduceType;
    #[MapOutputName('sweetPotatoUW')]
    public float $sweetPotatoUnitWeight;
    #[MapOutputName('sweetPotatoUP')]
    public float $sweetPotatoUnitPrice;

    #[MapOutputName('maxInv')]
    public float $maxInvestment;

    #[MapOutputName('method_harrowing')]
    public string $methodHarrowing;
    #[MapOutputName('method_ploughing')]
    public string $methodPloughing;
    #[MapOutputName('method_ridging')]
    public string $methodRidging;

    #[MapOutputName('nameSF')]
    public string $starchFactoryName;

    #[MapOutputName('ploughing')]
    public bool $ploughing;
    #[MapOutputName('problemWeeds')]
    public bool $problemWeeds;
    #[MapOutputName('ridging')]
    public bool $ridging;

    #[MapOutputName('riskAtt')]
    public int $riskAttitude;
    #[MapOutputName('saleSF')]
    public bool $sellToStarchFactory;

    #[MapOutputName('tractor_harrow')]
    public bool $tractorHarrow;
    #[MapOutputName('tractor_plough')]
    public bool $tractorPlough;
    #[MapOutputName('tractor_ridger')]
    public bool $tractorRidger;

    #[MapOutputName('userEmail')]
    public string $emailAddress;

    #[MapOutputName('userField')]
    public string $farmName;

    #[MapOutputName('userName')]
    public string $userName;

    #[MapOutputName('userPhoneCC')]
    public string $phoneCountryCode;

    #[MapOutputName('userPhoneNr')]
    public string $phoneNumber;

    //now for fertilizers
    #[MapOutputName('ureaavailable')]
    public bool $ureaAvailable = false;

    #[MapOutputName('ureaCostperBag')]
    public float $ureaCostPerBag = 0.0;

    #[MapOutputName('ureaBagWt')]
    public int $ureaBagWeight = 50;

    #[MapOutputName('MOPavailable')]
    public bool $mopAvailable = false;

    #[MapOutputName('MOPCostperBag')]
    public float $mopCostPerBag = 0.0;

    #[MapOutputName('MOPBagWt')]
    public int $mopBagWeight = 50;

    #[MapOutputName('DAPavailable')]
    public bool $dapAvailable = false;

    #[MapOutputName('DAPCostperBag')]
    public float $dapCostPerBag = 0.0;

    #[MapOutputName('DAPBagWt')]
    public int $dapBagWeight = 50;

    #[MapOutputName('NPK201010available')]
    public bool $npk201010Available = false;

    #[MapOutputName('NPK201010CostperBag')]
    public float $npk201010CostPerBag = 0.0;

    #[MapOutputName('NPK201010BagWt')]
    public int $npk201010BagWeight = 50;

    #[MapOutputName('NPK151515available')]
    public bool $npk151515Available = false;

    #[MapOutputName('NPK151515CostperBag')]
    public float $npk151515CostPerBag = 0.0;

    #[MapOutputName('NPK151515BagWt')]
    public int $npk151515BagWeight = 50;

    #[MapOutputName('NPK201226available')]
    public bool $npk201226Available = false;

    #[MapOutputName('NPK201226CostperBag')]
    public float $npk201226CostPerBag = 0.0;

    #[MapOutputName('NPK201226BagWt')]
    public int $npk201226BagWeight = 50;

    #[MapOutputName('NPK201216available')]
    public bool $npk201216Available = false;

    #[MapOutputName('NPK201216CostperBag')]
    public float $npk201216CostPerBag = 0.0;

    #[MapOutputName('NPK201216BagWt')]
    public int $npk201216BagWeight = 50;

    #[MapOutputName('NPK112221available')]
    public bool $npk112221Available = false;

    #[MapOutputName('NPK112221CostperBag')]
    public float $npk112221CostPerBag = 0.0;

    #[MapOutputName('NPK112221BagWt')]
    public int $npk112221BagWeight = 50;

    #[MapOutputName('NPK251010available')]
    public bool $npk251010Available = false;

    #[MapOutputName('NPK251010CostperBag')]
    public float $npk251010CostPerBag = 0.0;

    #[MapOutputName('NPK251010BagWt')]
    public int $npk251010BagWeight = 50;

    #[MapOutputName('NPK152020available')]
    public bool $npk152020Available = false;

    #[MapOutputName('NPK152020CostperBag')]
    public float $npk152020CostPerBag = 0.0;

    #[MapOutputName('NPK152020BagWt')]
    public int $npk152020BagWeight = 50;

    #[MapOutputName('NPK23105available')]
    public bool $npk23105Available = false;

    #[MapOutputName('NPK23105CostperBag')]
    public float $npk23105CostPerBag = 0.0;

    #[MapOutputName('NPK23105BagWt')]
    public int $npk23105BagWeight = 50;

    #[MapOutputName('NPK123017available')]
    public bool $npk123017Available = false;

    #[MapOutputName('NPK123017CostperBag')]
    public float $npk123017CostPerBag = 0.0;

    #[MapOutputName('NPK123017BagWt')]
    public int $npk123017BagWeight = 50;

    #[MapOutputName('FOMIBAGARAavailable')]
    public bool $fomiBagaraAvailable = false;

    #[MapOutputName('FOMIBAGARACostperBag')]
    public float $fomiBagaraCostPerBag = 0.0;

    #[MapOutputName('FOMIBAGARABagWt')]
    public int $fomiBagaraBagWeight = 50;

    #[MapOutputName('FOMIIMBURAavailable')]
    public bool $fomiImburaAvailable = false;

    #[MapOutputName('FOMIIMBURACostperBag')]
    public float $fomiImburaCostPerBag = 0.0;

    #[MapOutputName('FOMIIMBURABagWt')]
    public int $fomiImburaBagWeight = 50;

    #[MapOutputName('FOMITOTAHAZAavailable')]
    public bool $fomiTotahazaAvailable = false;

    #[MapOutputName('FOMITOTAHAZACostperBag')]
    public float $fomiTotahazaCostPerBag = 0.0;

    #[MapOutputName('FOMITOTAHAZABagWt')]
    public int $fomiTotahazaBagWeight = 50;

    #[MapOutputName('DOLOMITEAavailable')]
    public bool $dolomiteAAvailable = false;

    #[MapOutputName('DOLOMITECostperBag')]
    public float $dolomiteCostPerBag = 0.0;

    #[MapOutputName('DOLOMITEBagWt')]
    public int $dolomiteBagWeight = 50;

    #[MapOutputName('TSPavailable')]
    public bool $tspAvailable = false;

    #[MapOutputName('TSPCostperBag')]
    public float $tspCostPerBag = 0.0;

    #[MapOutputName('TSPBagWt')]
    public int $tspBagWeight = 50;

    #[MapOutputName('NPK171717available')]
    public bool $npk171717Available = false;

    #[MapOutputName('NPK171717CostperBag')]
    public float $npk171717CostPerBag = 0.0;

    #[MapOutputName('NPK171717BagWt')]
    public int $npk171717BagWeight = 50;

    #[MapOutputName('YaraMila_UNIKavailable')]
    public bool $yaraMilaUnikAvailable = false;

    #[MapOutputName('YaraMila_UNIKCostperBag')]
    public float $yaraMilaUnikCostPerBag = 0.0;

    #[MapOutputName('YaraMila_UNIKBagWt')]
    public int $yaraMilaUnikBagWeight = 50;

    #[MapOutputName('CANavailable')]
    public bool $canAvailable = false;

    #[MapOutputName('CANCostperBag')]
    public float $canCostPerBag = 0.0;

    #[MapOutputName('CANBagWt')]
    public int $canBagWeight = 50;

    #[MapOutputName('SSPavailable')]
    public bool $sspAvailable = false;

    #[MapOutputName('SSPCostperBag')]
    public float $sspCostPerBag = 0.0;

    #[MapOutputName('SSPBagWt')]
    public int $sspBagWeight = 50;

}
