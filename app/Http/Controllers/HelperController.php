<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class HelperController extends Controller
{

    static public function restoreLogos()
    {
        try {

            $entidades = [
                [
                    "id" => 1,
                    "id_entidad" => null
                ],
                [
                    "id" => 603,
                    "id_entidad" => null
                ],
                [
                    "id" => 602,
                    "id_entidad" => null
                ],
                [
                    "id" => 604,
                    "id_entidad" => null
                ],
                [
                    "id" => 134668,
                    "id_entidad" => 206856
                ],
                [
                    "id" => 134685,
                    "id_entidad" => 206909
                ],
                [
                    "id" => 134686,
                    "id_entidad" => 206910
                ],
                [
                    "id" => 134687,
                    "id_entidad" => 206911
                ],
                [
                    "id" => 134688,
                    "id_entidad" => 206912
                ],
                [
                    "id" => 134689,
                    "id_entidad" => 206913
                ],
                [
                    "id" => 134690,
                    "id_entidad" => 206914
                ],
                [
                    "id" => 134691,
                    "id_entidad" => 206915
                ],
                [
                    "id" => 134692,
                    "id_entidad" => 206916
                ],
                [
                    "id" => 134693,
                    "id_entidad" => 206917
                ],
                [
                    "id" => 134694,
                    "id_entidad" => 206918
                ],
                [
                    "id" => 134695,
                    "id_entidad" => 206919
                ],
                [
                    "id" => 134696,
                    "id_entidad" => 206920
                ],
                [
                    "id" => 134697,
                    "id_entidad" => 206921
                ],
                [
                    "id" => 134698,
                    "id_entidad" => 206922
                ],
                [
                    "id" => 134699,
                    "id_entidad" => 206923
                ],
                [
                    "id" => 134700,
                    "id_entidad" => 207084
                ],
                [
                    "id" => 134701,
                    "id_entidad" => 206924
                ],
                [
                    "id" => 134702,
                    "id_entidad" => 206925
                ],
                [
                    "id" => 134703,
                    "id_entidad" => 206926
                ],
                [
                    "id" => 134704,
                    "id_entidad" => 206927
                ],
                [
                    "id" => 134705,
                    "id_entidad" => 207085
                ],
                [
                    "id" => 134706,
                    "id_entidad" => 206928
                ],
                [
                    "id" => 134707,
                    "id_entidad" => 206929
                ],
                [
                    "id" => 134708,
                    "id_entidad" => 206930
                ],
                [
                    "id" => 134709,
                    "id_entidad" => 207086
                ],
                [
                    "id" => 134710,
                    "id_entidad" => 206931
                ],
                [
                    "id" => 134711,
                    "id_entidad" => 206932
                ],
                [
                    "id" => 134712,
                    "id_entidad" => 206933
                ],
                [
                    "id" => 134713,
                    "id_entidad" => 206934
                ],
                [
                    "id" => 134714,
                    "id_entidad" => 207087
                ],
                [
                    "id" => 134715,
                    "id_entidad" => 206935
                ],
                [
                    "id" => 134716,
                    "id_entidad" => 206936
                ],
                [
                    "id" => 134717,
                    "id_entidad" => 206937
                ],
                [
                    "id" => 134718,
                    "id_entidad" => 207088
                ],
                [
                    "id" => 134719,
                    "id_entidad" => 206938
                ],
                [
                    "id" => 134720,
                    "id_entidad" => 206939
                ],
                [
                    "id" => 134721,
                    "id_entidad" => 206940
                ],
                [
                    "id" => 134722,
                    "id_entidad" => 206941
                ],
                [
                    "id" => 134723,
                    "id_entidad" => 206942
                ],
                [
                    "id" => 134724,
                    "id_entidad" => 206943
                ],
                [
                    "id" => 134725,
                    "id_entidad" => 206944
                ],
                [
                    "id" => 134726,
                    "id_entidad" => 206945
                ],
                [
                    "id" => 134727,
                    "id_entidad" => 206946
                ],
                [
                    "id" => 134728,
                    "id_entidad" => 206947
                ],
                [
                    "id" => 134729,
                    "id_entidad" => 206948
                ],
                [
                    "id" => 134730,
                    "id_entidad" => 206949
                ],
                [
                    "id" => 134731,
                    "id_entidad" => 207089
                ],
                [
                    "id" => 134732,
                    "id_entidad" => 206950
                ],
                [
                    "id" => 134733,
                    "id_entidad" => 206951
                ],
                [
                    "id" => 134734,
                    "id_entidad" => 206952
                ],
                [
                    "id" => 134735,
                    "id_entidad" => 207090
                ],
                [
                    "id" => 134736,
                    "id_entidad" => 206953
                ],
                [
                    "id" => 134737,
                    "id_entidad" => 207091
                ],
                [
                    "id" => 134738,
                    "id_entidad" => 206954
                ],
                [
                    "id" => 134739,
                    "id_entidad" => 206955
                ],
                [
                    "id" => 134740,
                    "id_entidad" => 206956
                ],
                [
                    "id" => 134741,
                    "id_entidad" => 206957
                ],
                [
                    "id" => 134742,
                    "id_entidad" => 206958
                ],
                [
                    "id" => 134743,
                    "id_entidad" => 206959
                ],
                [
                    "id" => 134744,
                    "id_entidad" => 206960
                ],
                [
                    "id" => 134745,
                    "id_entidad" => 206961
                ],
                [
                    "id" => 134746,
                    "id_entidad" => 206962
                ],
                [
                    "id" => 134747,
                    "id_entidad" => 206963
                ],
                [
                    "id" => 134748,
                    "id_entidad" => 206964
                ],
                [
                    "id" => 134749,
                    "id_entidad" => 207092
                ],
                [
                    "id" => 134750,
                    "id_entidad" => 206965
                ],
                [
                    "id" => 134751,
                    "id_entidad" => 206966
                ],
                [
                    "id" => 134752,
                    "id_entidad" => 206967
                ],
                [
                    "id" => 134753,
                    "id_entidad" => 207093
                ],
                [
                    "id" => 134754,
                    "id_entidad" => 207094
                ],
                [
                    "id" => 134755,
                    "id_entidad" => 206968
                ],
                [
                    "id" => 134756,
                    "id_entidad" => 206969
                ],
                [
                    "id" => 134757,
                    "id_entidad" => 206970
                ],
                [
                    "id" => 134758,
                    "id_entidad" => 206971
                ],
                [
                    "id" => 134759,
                    "id_entidad" => 206972
                ],
                [
                    "id" => 134760,
                    "id_entidad" => 207095
                ],
                [
                    "id" => 134761,
                    "id_entidad" => 206973
                ],
                [
                    "id" => 134762,
                    "id_entidad" => 206974
                ],
                [
                    "id" => 134763,
                    "id_entidad" => 206975
                ],
                [
                    "id" => 134764,
                    "id_entidad" => 207096
                ],
                [
                    "id" => 134765,
                    "id_entidad" => 206976
                ],
                [
                    "id" => 134766,
                    "id_entidad" => 206977
                ],
                [
                    "id" => 134767,
                    "id_entidad" => 206978
                ],
                [
                    "id" => 134768,
                    "id_entidad" => 207097
                ],
                [
                    "id" => 134769,
                    "id_entidad" => 207098
                ],
                [
                    "id" => 134770,
                    "id_entidad" => 207099
                ],
                [
                    "id" => 134771,
                    "id_entidad" => 206979
                ],
                [
                    "id" => 134772,
                    "id_entidad" => 206980
                ],
                [
                    "id" => 134773,
                    "id_entidad" => 206981
                ],
                [
                    "id" => 134774,
                    "id_entidad" => 206982
                ],
                [
                    "id" => 134775,
                    "id_entidad" => 206983
                ],
                [
                    "id" => 134776,
                    "id_entidad" => 206984
                ],
                [
                    "id" => 134777,
                    "id_entidad" => 206985
                ],
                [
                    "id" => 134778,
                    "id_entidad" => 206986
                ],
                [
                    "id" => 134779,
                    "id_entidad" => 206987
                ],
                [
                    "id" => 134780,
                    "id_entidad" => 206988
                ],
                [
                    "id" => 134781,
                    "id_entidad" => 206989
                ],
                [
                    "id" => 134782,
                    "id_entidad" => 206990
                ],
                [
                    "id" => 134783,
                    "id_entidad" => 207100
                ],
                [
                    "id" => 134784,
                    "id_entidad" => 206991
                ],
                [
                    "id" => 134785,
                    "id_entidad" => 207101
                ],
                [
                    "id" => 134786,
                    "id_entidad" => 207102
                ],
                [
                    "id" => 134787,
                    "id_entidad" => 206992
                ],
                [
                    "id" => 134788,
                    "id_entidad" => 206993
                ],
                [
                    "id" => 134789,
                    "id_entidad" => 206994
                ],
                [
                    "id" => 134790,
                    "id_entidad" => 207103
                ],
                [
                    "id" => 134791,
                    "id_entidad" => 206995
                ],
                [
                    "id" => 134792,
                    "id_entidad" => 206996
                ],
                [
                    "id" => 134793,
                    "id_entidad" => 207104
                ],
                [
                    "id" => 134794,
                    "id_entidad" => 206997
                ],
                [
                    "id" => 134795,
                    "id_entidad" => 206998
                ],
                [
                    "id" => 134796,
                    "id_entidad" => 206999
                ],
                [
                    "id" => 134797,
                    "id_entidad" => 207105
                ],
                [
                    "id" => 134798,
                    "id_entidad" => 207106
                ],
                [
                    "id" => 134799,
                    "id_entidad" => 207000
                ],
                [
                    "id" => 134800,
                    "id_entidad" => 207001
                ],
                [
                    "id" => 134801,
                    "id_entidad" => 207002
                ],
                [
                    "id" => 134802,
                    "id_entidad" => 207003
                ],
                [
                    "id" => 134803,
                    "id_entidad" => 207004
                ],
                [
                    "id" => 134804,
                    "id_entidad" => 207005
                ],
                [
                    "id" => 134805,
                    "id_entidad" => 207107
                ],
                [
                    "id" => 134806,
                    "id_entidad" => 207006
                ],
                [
                    "id" => 134807,
                    "id_entidad" => 207007
                ],
                [
                    "id" => 134808,
                    "id_entidad" => 207008
                ],
                [
                    "id" => 134809,
                    "id_entidad" => 207009
                ],
                [
                    "id" => 134810,
                    "id_entidad" => 207010
                ],
                [
                    "id" => 134811,
                    "id_entidad" => 207108
                ],
                [
                    "id" => 134812,
                    "id_entidad" => 207011
                ],
                [
                    "id" => 134813,
                    "id_entidad" => 207012
                ],
                [
                    "id" => 134814,
                    "id_entidad" => 207013
                ],
                [
                    "id" => 134815,
                    "id_entidad" => 207014
                ],
                [
                    "id" => 134816,
                    "id_entidad" => 207015
                ],
                [
                    "id" => 134817,
                    "id_entidad" => 207016
                ],
                [
                    "id" => 134818,
                    "id_entidad" => 207017
                ],
                [
                    "id" => 134819,
                    "id_entidad" => 207018
                ],
                [
                    "id" => 134820,
                    "id_entidad" => 207019
                ],
                [
                    "id" => 134821,
                    "id_entidad" => 207020
                ],
                [
                    "id" => 134822,
                    "id_entidad" => 207021
                ],
                [
                    "id" => 134823,
                    "id_entidad" => 207022
                ],
                [
                    "id" => 134824,
                    "id_entidad" => 207109
                ],
                [
                    "id" => 134825,
                    "id_entidad" => 207023
                ],
                [
                    "id" => 134826,
                    "id_entidad" => 207024
                ],
                [
                    "id" => 134827,
                    "id_entidad" => 207025
                ],
                [
                    "id" => 134828,
                    "id_entidad" => 207110
                ],
                [
                    "id" => 134829,
                    "id_entidad" => 207026
                ],
                [
                    "id" => 134830,
                    "id_entidad" => 207027
                ],
                [
                    "id" => 134831,
                    "id_entidad" => 207111
                ],
                [
                    "id" => 134832,
                    "id_entidad" => 207112
                ],
                [
                    "id" => 134833,
                    "id_entidad" => 207028
                ],
                [
                    "id" => 134834,
                    "id_entidad" => 207029
                ],
                [
                    "id" => 134835,
                    "id_entidad" => 207113
                ],
                [
                    "id" => 134836,
                    "id_entidad" => 207114
                ],
                [
                    "id" => 134837,
                    "id_entidad" => 207030
                ],
                [
                    "id" => 134838,
                    "id_entidad" => 207031
                ],
                [
                    "id" => 134839,
                    "id_entidad" => 207032
                ],
                [
                    "id" => 134840,
                    "id_entidad" => 207033
                ],
                [
                    "id" => 134841,
                    "id_entidad" => 207034
                ],
                [
                    "id" => 134842,
                    "id_entidad" => 207035
                ],
                [
                    "id" => 134843,
                    "id_entidad" => 207036
                ],
                [
                    "id" => 134844,
                    "id_entidad" => 207037
                ],
                [
                    "id" => 134845,
                    "id_entidad" => 207038
                ],
                [
                    "id" => 134846,
                    "id_entidad" => 207039
                ],
                [
                    "id" => 134847,
                    "id_entidad" => 207040
                ],
                [
                    "id" => 134848,
                    "id_entidad" => 207041
                ],
                [
                    "id" => 134849,
                    "id_entidad" => 207115
                ],
                [
                    "id" => 134850,
                    "id_entidad" => 207116
                ],
                [
                    "id" => 134851,
                    "id_entidad" => 207117
                ],
                [
                    "id" => 134852,
                    "id_entidad" => 207042
                ],
                [
                    "id" => 134853,
                    "id_entidad" => 207118
                ],
                [
                    "id" => 134854,
                    "id_entidad" => 207043
                ],
                [
                    "id" => 134855,
                    "id_entidad" => 207044
                ],
                [
                    "id" => 134856,
                    "id_entidad" => 207119
                ],
                [
                    "id" => 134857,
                    "id_entidad" => 207045
                ],
                [
                    "id" => 134858,
                    "id_entidad" => 207120
                ],
                [
                    "id" => 134859,
                    "id_entidad" => 207121
                ],
                [
                    "id" => 134860,
                    "id_entidad" => 207046
                ],
                [
                    "id" => 134861,
                    "id_entidad" => 207047
                ],
                [
                    "id" => 134862,
                    "id_entidad" => 207048
                ],
                [
                    "id" => 134863,
                    "id_entidad" => 207049
                ],
                [
                    "id" => 134864,
                    "id_entidad" => 207050
                ],
                [
                    "id" => 134865,
                    "id_entidad" => 207051
                ],
                [
                    "id" => 134866,
                    "id_entidad" => 207052
                ],
                [
                    "id" => 134867,
                    "id_entidad" => 207053
                ],
                [
                    "id" => 134868,
                    "id_entidad" => 207122
                ],
                [
                    "id" => 134869,
                    "id_entidad" => 207054
                ],
                [
                    "id" => 134870,
                    "id_entidad" => 207055
                ],
                [
                    "id" => 134871,
                    "id_entidad" => 207056
                ],
                [
                    "id" => 134872,
                    "id_entidad" => 207057
                ],
                [
                    "id" => 134873,
                    "id_entidad" => 207058
                ],
                [
                    "id" => 134874,
                    "id_entidad" => 207060
                ],
                [
                    "id" => 134875,
                    "id_entidad" => 207061
                ],
                [
                    "id" => 134876,
                    "id_entidad" => 207062
                ],
                [
                    "id" => 134877,
                    "id_entidad" => 207063
                ],
                [
                    "id" => 134878,
                    "id_entidad" => 207064
                ],
                [
                    "id" => 134879,
                    "id_entidad" => 207065
                ],
                [
                    "id" => 134880,
                    "id_entidad" => 207066
                ],
                [
                    "id" => 134881,
                    "id_entidad" => 207067
                ],
                [
                    "id" => 134882,
                    "id_entidad" => 207068
                ],
                [
                    "id" => 134883,
                    "id_entidad" => 207123
                ],
                [
                    "id" => 134884,
                    "id_entidad" => 207069
                ],
                [
                    "id" => 134885,
                    "id_entidad" => 207070
                ],
                [
                    "id" => 134886,
                    "id_entidad" => 207071
                ],
                [
                    "id" => 134887,
                    "id_entidad" => 207124
                ],
                [
                    "id" => 134888,
                    "id_entidad" => 207072
                ],
                [
                    "id" => 134889,
                    "id_entidad" => 207274
                ],
                [
                    "id" => 134890,
                    "id_entidad" => 207074
                ],
                [
                    "id" => 134891,
                    "id_entidad" => 207075
                ],
                [
                    "id" => 134892,
                    "id_entidad" => 207076
                ],
                [
                    "id" => 134893,
                    "id_entidad" => 207077
                ],
                [
                    "id" => 134894,
                    "id_entidad" => 207078
                ],
                [
                    "id" => 134895,
                    "id_entidad" => 207079
                ],
                [
                    "id" => 134896,
                    "id_entidad" => 207125
                ],
                [
                    "id" => 134897,
                    "id_entidad" => 207080
                ],
                [
                    "id" => 134898,
                    "id_entidad" => 207081
                ],
                [
                    "id" => 134899,
                    "id_entidad" => 207082
                ],
                [
                    "id" => 134900,
                    "id_entidad" => 207083
                ],
                [
                    "id" => 134901,
                    "id_entidad" => 207073
                ],
                [
                    "id" => 134902,
                    "id_entidad" => 207245
                ],
                [
                    "id" => 134903,
                    "id_entidad" => 207275
                ],
                [
                    "id" => 134904,
                    "id_entidad" => 207276
                ],
                [
                    "id" => 134905,
                    "id_entidad" => 207277
                ],
                [
                    "id" => 134906,
                    "id_entidad" => 207278
                ],
                [
                    "id" => 134907,
                    "id_entidad" => 207279
                ],
                [
                    "id" => 134908,
                    "id_entidad" => 207280
                ],
                [
                    "id" => 134909,
                    "id_entidad" => 207281
                ],
                [
                    "id" => 134910,
                    "id_entidad" => 207282
                ],
                [
                    "id" => 134911,
                    "id_entidad" => 207283
                ],
                [
                    "id" => 134912,
                    "id_entidad" => 207284
                ],
                [
                    "id" => 134913,
                    "id_entidad" => 207285
                ],
                [
                    "id" => 134914,
                    "id_entidad" => 207286
                ],
                [
                    "id" => 134915,
                    "id_entidad" => 207287
                ],
                [
                    "id" => 134916,
                    "id_entidad" => 207288
                ],
                [
                    "id" => 134917,
                    "id_entidad" => 207289
                ],
                [
                    "id" => 134918,
                    "id_entidad" => 207290
                ],
                [
                    "id" => 134919,
                    "id_entidad" => 207291
                ],
                [
                    "id" => 134920,
                    "id_entidad" => 207292
                ],
                [
                    "id" => 134921,
                    "id_entidad" => 207293
                ],
                [
                    "id" => 134922,
                    "id_entidad" => 207294
                ],
                [
                    "id" => 134923,
                    "id_entidad" => 207295
                ],
                [
                    "id" => 134924,
                    "id_entidad" => 207128
                ],
                [
                    "id" => 134925,
                    "id_entidad" => 207296
                ],
                [
                    "id" => 134926,
                    "id_entidad" => 207138
                ],
                [
                    "id" => 134927,
                    "id_entidad" => 207134
                ],
                [
                    "id" => 134928,
                    "id_entidad" => 207297
                ],
                [
                    "id" => 134929,
                    "id_entidad" => 207239
                ],
                [
                    "id" => 134930,
                    "id_entidad" => 207253
                ],
                [
                    "id" => 134931,
                    "id_entidad" => 207261
                ],
                [
                    "id" => 134932,
                    "id_entidad" => 207142
                ],
                [
                    "id" => 134933,
                    "id_entidad" => 207126
                ],
                [
                    "id" => 134934,
                    "id_entidad" => 207299
                ],
                [
                    "id" => 134935,
                    "id_entidad" => 207300
                ],
                [
                    "id" => 134936,
                    "id_entidad" => 207301
                ],
                [
                    "id" => 134937,
                    "id_entidad" => 207302
                ],
                [
                    "id" => 134938,
                    "id_entidad" => 207185
                ],
                [
                    "id" => 134939,
                    "id_entidad" => 207303
                ],
                [
                    "id" => 134940,
                    "id_entidad" => 207304
                ],
                [
                    "id" => 134941,
                    "id_entidad" => 207166
                ],
                [
                    "id" => 134942,
                    "id_entidad" => 207184
                ],
                [
                    "id" => 134943,
                    "id_entidad" => 207305
                ],
                [
                    "id" => 134944,
                    "id_entidad" => 207306
                ],
                [
                    "id" => 134945,
                    "id_entidad" => 207307
                ],
                [
                    "id" => 134946,
                    "id_entidad" => 207308
                ],
                [
                    "id" => 134947,
                    "id_entidad" => 207309
                ],
                [
                    "id" => 134948,
                    "id_entidad" => 207310
                ],
                [
                    "id" => 134949,
                    "id_entidad" => 207312
                ],
                [
                    "id" => 134950,
                    "id_entidad" => 207313
                ],
                [
                    "id" => 134951,
                    "id_entidad" => 207314
                ],
                [
                    "id" => 134952,
                    "id_entidad" => 207315
                ],
                [
                    "id" => 134953,
                    "id_entidad" => 207316
                ],
                [
                    "id" => 134954,
                    "id_entidad" => 207317
                ],
                [
                    "id" => 134955,
                    "id_entidad" => 207318
                ],
                [
                    "id" => 134956,
                    "id_entidad" => 207319
                ],
                [
                    "id" => 134957,
                    "id_entidad" => 207320
                ],
                [
                    "id" => 134958,
                    "id_entidad" => 207153
                ],
                [
                    "id" => 134959,
                    "id_entidad" => 207321
                ],
                [
                    "id" => 134960,
                    "id_entidad" => 207145
                ],
                [
                    "id" => 134961,
                    "id_entidad" => 207322
                ],
                [
                    "id" => 134962,
                    "id_entidad" => 207226
                ],
                [
                    "id" => 134963,
                    "id_entidad" => 207324
                ],
                [
                    "id" => 134964,
                    "id_entidad" => 207178
                ],
                [
                    "id" => 134965,
                    "id_entidad" => 207325
                ],
                [
                    "id" => 134966,
                    "id_entidad" => 207327
                ],
                [
                    "id" => 134967,
                    "id_entidad" => null
                ],
                [
                    "id" => 134968,
                    "id_entidad" => 210366
                ],
                [
                    "id" => 134969,
                    "id_entidad" => 210367
                ],
                [
                    "id" => 134970,
                    "id_entidad" => 207948
                ],
                [
                    "id" => 134971,
                    "id_entidad" => 210368
                ],
                [
                    "id" => 134972,
                    "id_entidad" => 210369
                ],
                [
                    "id" => 134973,
                    "id_entidad" => 207140
                ],
                [
                    "id" => 134974,
                    "id_entidad" => 208206
                ],
                [
                    "id" => 134975,
                    "id_entidad" => 210370
                ],
                [
                    "id" => 134976,
                    "id_entidad" => 210371
                ],
                [
                    "id" => 134977,
                    "id_entidad" => 210372
                ],
                [
                    "id" => 134978,
                    "id_entidad" => 207186
                ],
                [
                    "id" => 134979,
                    "id_entidad" => 210373
                ],
                [
                    "id" => 134980,
                    "id_entidad" => 210374
                ],
                [
                    "id" => 134981,
                    "id_entidad" => 208501
                ],
                [
                    "id" => 134982,
                    "id_entidad" => 210375
                ],
                [
                    "id" => 134983,
                    "id_entidad" => 207233
                ],
                [
                    "id" => 134984,
                    "id_entidad" => 210376
                ],
                [
                    "id" => 134985,
                    "id_entidad" => 210377
                ],
                [
                    "id" => 134986,
                    "id_entidad" => 210378
                ],
                [
                    "id" => 134987,
                    "id_entidad" => 207263
                ],
                [
                    "id" => 134988,
                    "id_entidad" => 210379
                ],
                [
                    "id" => 134989,
                    "id_entidad" => 210380
                ],
                [
                    "id" => 134990,
                    "id_entidad" => 210382
                ],
                [
                    "id" => 134991,
                    "id_entidad" => 210383
                ],
                [
                    "id" => 134992,
                    "id_entidad" => 210384
                ],
                [
                    "id" => 134993,
                    "id_entidad" => 210385
                ],
                [
                    "id" => 134994,
                    "id_entidad" => 207144
                ],
                [
                    "id" => 134995,
                    "id_entidad" => 210386
                ],
                [
                    "id" => 134996,
                    "id_entidad" => 210387
                ],
                [
                    "id" => 134997,
                    "id_entidad" => 210389
                ],
                [
                    "id" => 134998,
                    "id_entidad" => 210390
                ],
                [
                    "id" => 134999,
                    "id_entidad" => 210391
                ],
                [
                    "id" => 135000,
                    "id_entidad" => 207177
                ],
                [
                    "id" => 135001,
                    "id_entidad" => 210392
                ],
                [
                    "id" => 135002,
                    "id_entidad" => 210393
                ],
                [
                    "id" => 135003,
                    "id_entidad" => 210394
                ],
                [
                    "id" => 135004,
                    "id_entidad" => 207165
                ],
                [
                    "id" => 135005,
                    "id_entidad" => 210395
                ],
                [
                    "id" => 135006,
                    "id_entidad" => 210396
                ],
                [
                    "id" => 135007,
                    "id_entidad" => 210397
                ],
                [
                    "id" => 135008,
                    "id_entidad" => 207183
                ],
                [
                    "id" => 135009,
                    "id_entidad" => 210406
                ],
                [
                    "id" => 135010,
                    "id_entidad" => 210398
                ],
                [
                    "id" => 135011,
                    "id_entidad" => 210399
                ],
                [
                    "id" => 135012,
                    "id_entidad" => 210400
                ],
                [
                    "id" => 135013,
                    "id_entidad" => 208215
                ],
                [
                    "id" => 135014,
                    "id_entidad" => 210403
                ],
                [
                    "id" => 135015,
                    "id_entidad" => 210404
                ],
                [
                    "id" => 135016,
                    "id_entidad" => 210405
                ],
                [
                    "id" => 135017,
                    "id_entidad" => 210408
                ],
                [
                    "id" => 135018,
                    "id_entidad" => 210409
                ],
                [
                    "id" => 135019,
                    "id_entidad" => 210410
                ],
                [
                    "id" => 135020,
                    "id_entidad" => 210411
                ],
                [
                    "id" => 135021,
                    "id_entidad" => 210412
                ],
                [
                    "id" => 135022,
                    "id_entidad" => 210413
                ],
                [
                    "id" => 135023,
                    "id_entidad" => 210414
                ],
                [
                    "id" => 135024,
                    "id_entidad" => 210415
                ],
                [
                    "id" => 135025,
                    "id_entidad" => 210416
                ],
                [
                    "id" => 135026,
                    "id_entidad" => 207071
                ],
                [
                    "id" => 135027,
                    "id_entidad" => 207274
                ],
                [
                    "id" => 135028,
                    "id_entidad" => 210417
                ],
                [
                    "id" => 135029,
                    "id_entidad" => 210418
                ],
                [
                    "id" => 135030,
                    "id_entidad" => 210419
                ],
                [
                    "id" => 135031,
                    "id_entidad" => 210407
                ],
                [
                    "id" => 135032,
                    "id_entidad" => 209330
                ],
                [
                    "id" => 135033,
                    "id_entidad" => 210420
                ],
                [
                    "id" => 135034,
                    "id_entidad" => 210421
                ],
                [
                    "id" => 135035,
                    "id_entidad" => 210422
                ],
                [
                    "id" => 135036,
                    "id_entidad" => 210425
                ],
                [
                    "id" => 135037,
                    "id_entidad" => 210426
                ],
                [
                    "id" => 135038,
                    "id_entidad" => 207174
                ],
                [
                    "id" => 135039,
                    "id_entidad" => 210427
                ],
                [
                    "id" => 135040,
                    "id_entidad" => 210428
                ],
                [
                    "id" => 135041,
                    "id_entidad" => 210429
                ],
                [
                    "id" => 135042,
                    "id_entidad" => 210430
                ],
                [
                    "id" => 605,
                    "id_entidad" => null
                ],
                [
                    "id" => 606,
                    "id_entidad" => null
                ],
                [
                    "id" => 135043,
                    "id_entidad" => null
                ],
                [
                    "id" => 135044,
                    "id_entidad" => null
                ],
                [
                    "id" => 135045,
                    "id_entidad" => 207222
                ],
                [
                    "id" => 135046,
                    "id_entidad" => 210431
                ],
                [
                    "id" => 135047,
                    "id_entidad" => 210432
                ],
                [
                    "id" => 135048,
                    "id_entidad" => 210433
                ],
                [
                    "id" => 135049,
                    "id_entidad" => 210434
                ],
                [
                    "id" => 135050,
                    "id_entidad" => 210437
                ],
                [
                    "id" => 135051,
                    "id_entidad" => 210435
                ],
                [
                    "id" => 135052,
                    "id_entidad" => 210436
                ],
                [
                    "id" => 135053,
                    "id_entidad" => 207232
                ],
                [
                    "id" => 135054,
                    "id_entidad" => 210441
                ],
                [
                    "id" => 135055,
                    "id_entidad" => 210442
                ],
                [
                    "id" => 135056,
                    "id_entidad" => 207163
                ],
                [
                    "id" => 135057,
                    "id_entidad" => 210443
                ],
                [
                    "id" => 135058,
                    "id_entidad" => 210445
                ],
                [
                    "id" => 135059,
                    "id_entidad" => 210444
                ],
                [
                    "id" => 135060,
                    "id_entidad" => 210438
                ],
                [
                    "id" => 135061,
                    "id_entidad" => 210448
                ],
                [
                    "id" => 135062,
                    "id_entidad" => 210446
                ],
                [
                    "id" => 135063,
                    "id_entidad" => 210447
                ],
                [
                    "id" => 135064,
                    "id_entidad" => 210449
                ],
                [
                    "id" => 135065,
                    "id_entidad" => 210450
                ],
                [
                    "id" => 135066,
                    "id_entidad" => 210451
                ],
                [
                    "id" => 135067,
                    "id_entidad" => 208402
                ],
                [
                    "id" => 135068,
                    "id_entidad" => 210452
                ],
                [
                    "id" => 135069,
                    "id_entidad" => 210453
                ],
                [
                    "id" => 135070,
                    "id_entidad" => 210454
                ],
                [
                    "id" => 135071,
                    "id_entidad" => 210456
                ],
                [
                    "id" => 135072,
                    "id_entidad" => 210455
                ],
                [
                    "id" => 135073,
                    "id_entidad" => 210457
                ],
                [
                    "id" => 135074,
                    "id_entidad" => 210458
                ],
                [
                    "id" => 135075,
                    "id_entidad" => 210460
                ],
                [
                    "id" => 135076,
                    "id_entidad" => 210459
                ],
                [
                    "id" => 135077,
                    "id_entidad" => 210461
                ],
                [
                    "id" => 135078,
                    "id_entidad" => 210462
                ],
                [
                    "id" => 135079,
                    "id_entidad" => 210463
                ],
                [
                    "id" => 135080,
                    "id_entidad" => 210464
                ],
                [
                    "id" => 135081,
                    "id_entidad" => 210465
                ],
                [
                    "id" => 135082,
                    "id_entidad" => 210467
                ],
                [
                    "id" => 135083,
                    "id_entidad" => 210468
                ],
                [
                    "id" => 135084,
                    "id_entidad" => 210469
                ],
                [
                    "id" => 135085,
                    "id_entidad" => 208526
                ],
                [
                    "id" => 135086,
                    "id_entidad" => 210470
                ],
                [
                    "id" => 135087,
                    "id_entidad" => 208634
                ],
                [
                    "id" => 135088,
                    "id_entidad" => 210475
                ],
                [
                    "id" => 135089,
                    "id_entidad" => 210476
                ],
                [
                    "id" => 135090,
                    "id_entidad" => 210477
                ],
                [
                    "id" => 135091,
                    "id_entidad" => 207234
                ],
                [
                    "id" => 135092,
                    "id_entidad" => 210482
                ],
                [
                    "id" => 135093,
                    "id_entidad" => 210481
                ],
                [
                    "id" => 135094,
                    "id_entidad" => 210479
                ],
                [
                    "id" => 135095,
                    "id_entidad" => 210483
                ],
                [
                    "id" => 135096,
                    "id_entidad" => 207862
                ],
                [
                    "id" => 135097,
                    "id_entidad" => 207235
                ],
                [
                    "id" => 135098,
                    "id_entidad" => 210485
                ],
                [
                    "id" => 135099,
                    "id_entidad" => 210486
                ],
                [
                    "id" => 135100,
                    "id_entidad" => 210487
                ],
                [
                    "id" => 135101,
                    "id_entidad" => 210488
                ],
                [
                    "id" => 135102,
                    "id_entidad" => 210489
                ],
                [
                    "id" => 135103,
                    "id_entidad" => 210490
                ],
                [
                    "id" => 135104,
                    "id_entidad" => 210491
                ],
                [
                    "id" => 135105,
                    "id_entidad" => 210492
                ],
                [
                    "id" => 135106,
                    "id_entidad" => 210493
                ],
                [
                    "id" => 135107,
                    "id_entidad" => 210494
                ],
                [
                    "id" => 135108,
                    "id_entidad" => 210495
                ],
                [
                    "id" => 135109,
                    "id_entidad" => 210496
                ],
                [
                    "id" => 135110,
                    "id_entidad" => 210497
                ],
                [
                    "id" => 135111,
                    "id_entidad" => 210498
                ],
                [
                    "id" => 135112,
                    "id_entidad" => 210466
                ],
                [
                    "id" => 135113,
                    "id_entidad" => null
                ],
                [
                    "id" => 135114,
                    "id_entidad" => 210499
                ],
                [
                    "id" => 135115,
                    "id_entidad" => 210500
                ],
                [
                    "id" => 135116,
                    "id_entidad" => 210502
                ],
                [
                    "id" => 135117,
                    "id_entidad" => 210503
                ],
                [
                    "id" => 135118,
                    "id_entidad" => 210504
                ],
                [
                    "id" => 135119,
                    "id_entidad" => 210505
                ],
                [
                    "id" => 135120,
                    "id_entidad" => 208129
                ],
                [
                    "id" => 135121,
                    "id_entidad" => 210506
                ],
                [
                    "id" => 135122,
                    "id_entidad" => 210507
                ],
                [
                    "id" => 135123,
                    "id_entidad" => 210508
                ],
                [
                    "id" => 135124,
                    "id_entidad" => 210510
                ],
                [
                    "id" => 135125,
                    "id_entidad" => 210511
                ],
                [
                    "id" => 135126,
                    "id_entidad" => 210513
                ],
                [
                    "id" => 135127,
                    "id_entidad" => 210515
                ],
                [
                    "id" => 135128,
                    "id_entidad" => 210519
                ],
                [
                    "id" => 135129,
                    "id_entidad" => 210520
                ],
                [
                    "id" => 135130,
                    "id_entidad" => 208005
                ],
                [
                    "id" => 135131,
                    "id_entidad" => 210522
                ],
                [
                    "id" => 135132,
                    "id_entidad" => 210523
                ],
                [
                    "id" => 135133,
                    "id_entidad" => 210524
                ],
                [
                    "id" => 135134,
                    "id_entidad" => 210525
                ],
                [
                    "id" => 135135,
                    "id_entidad" => 210526
                ],
                [
                    "id" => 135136,
                    "id_entidad" => 210527
                ],
                [
                    "id" => 135137,
                    "id_entidad" => 207251
                ],
                [
                    "id" => 135138,
                    "id_entidad" => 210528
                ],
                [
                    "id" => 135139,
                    "id_entidad" => 210474
                ],
                [
                    "id" => 135140,
                    "id_entidad" => 210529
                ],
                [
                    "id" => 135141,
                    "id_entidad" => 207640
                ],
                [
                    "id" => 135142,
                    "id_entidad" => 210530
                ],
                [
                    "id" => 135143,
                    "id_entidad" => 210531
                ],
                [
                    "id" => 135144,
                    "id_entidad" => 210532
                ],
                [
                    "id" => 135145,
                    "id_entidad" => 210533
                ],
                [
                    "id" => 135146,
                    "id_entidad" => 210534
                ],
                [
                    "id" => 135147,
                    "id_entidad" => 207813
                ],
                [
                    "id" => 135148,
                    "id_entidad" => 210535
                ],
                [
                    "id" => 135149,
                    "id_entidad" => 210536
                ],
                [
                    "id" => 135150,
                    "id_entidad" => 210537
                ],
                [
                    "id" => 135151,
                    "id_entidad" => 210538
                ],
                [
                    "id" => 135152,
                    "id_entidad" => 210539
                ],
                [
                    "id" => 135153,
                    "id_entidad" => 210540
                ],
                [
                    "id" => 135154,
                    "id_entidad" => 210541
                ],
                [
                    "id" => 135155,
                    "id_entidad" => 210542
                ],
                [
                    "id" => 135156,
                    "id_entidad" => 210543
                ],
                [
                    "id" => 135157,
                    "id_entidad" => 210544
                ],
                [
                    "id" => 135158,
                    "id_entidad" => 210545
                ],
                [
                    "id" => 135159,
                    "id_entidad" => 210546
                ],
                [
                    "id" => 135160,
                    "id_entidad" => 210547
                ],
                [
                    "id" => 135161,
                    "id_entidad" => 210548
                ],
                [
                    "id" => 135162,
                    "id_entidad" => 210549
                ],
                [
                    "id" => 135163,
                    "id_entidad" => 210550
                ],
                [
                    "id" => 135164,
                    "id_entidad" => 210551
                ],
                [
                    "id" => 135165,
                    "id_entidad" => 210552
                ],
                [
                    "id" => 135166,
                    "id_entidad" => 210553
                ],
                [
                    "id" => 135167,
                    "id_entidad" => 210554
                ],
                [
                    "id" => 135168,
                    "id_entidad" => 210555
                ],
                [
                    "id" => 135169,
                    "id_entidad" => 210556
                ],
                [
                    "id" => 135170,
                    "id_entidad" => 210557
                ],
                [
                    "id" => 135171,
                    "id_entidad" => 210558
                ],
                [
                    "id" => 135172,
                    "id_entidad" => 210471
                ],
                [
                    "id" => 135173,
                    "id_entidad" => 210560
                ],
                [
                    "id" => 135174,
                    "id_entidad" => 210561
                ],
                [
                    "id" => 135175,
                    "id_entidad" => 210562
                ],
                [
                    "id" => 135176,
                    "id_entidad" => 210563
                ],
                [
                    "id" => 135177,
                    "id_entidad" => 210564
                ],
                [
                    "id" => 135178,
                    "id_entidad" => 210565
                ],
                [
                    "id" => 135179,
                    "id_entidad" => 210402
                ],
                [
                    "id" => 135180,
                    "id_entidad" => 210566
                ],
                [
                    "id" => 135181,
                    "id_entidad" => 210567
                ],
                [
                    "id" => 135182,
                    "id_entidad" => 210568
                ],
                [
                    "id" => 135183,
                    "id_entidad" => 210569
                ],
                [
                    "id" => 135184,
                    "id_entidad" => 210571
                ],
                [
                    "id" => 135185,
                    "id_entidad" => 210572
                ],
                [
                    "id" => 135186,
                    "id_entidad" => 207150
                ],
                [
                    "id" => 135187,
                    "id_entidad" => 210573
                ],
                [
                    "id" => 135188,
                    "id_entidad" => 207936
                ],
                [
                    "id" => 135189,
                    "id_entidad" => 210574
                ],
                [
                    "id" => 135190,
                    "id_entidad" => 210575
                ],
                [
                    "id" => 135191,
                    "id_entidad" => 210576
                ],
                [
                    "id" => 135192,
                    "id_entidad" => 210578
                ],
                [
                    "id" => 135193,
                    "id_entidad" => 210577
                ],
                [
                    "id" => 135194,
                    "id_entidad" => 210588
                ],
                [
                    "id" => 135195,
                    "id_entidad" => 210589
                ],
                [
                    "id" => 135196,
                    "id_entidad" => 210590
                ],
                [
                    "id" => 135197,
                    "id_entidad" => 210591
                ],
                [
                    "id" => 135198,
                    "id_entidad" => 210580
                ],
                [
                    "id" => 135199,
                    "id_entidad" => 210582
                ],
                [
                    "id" => 135200,
                    "id_entidad" => 210581
                ],
                [
                    "id" => 135201,
                    "id_entidad" => 210583
                ],
                [
                    "id" => 135202,
                    "id_entidad" => 210584
                ],
                [
                    "id" => 135203,
                    "id_entidad" => 210585
                ],
                [
                    "id" => 135204,
                    "id_entidad" => 210586
                ],
                [
                    "id" => 135205,
                    "id_entidad" => 210587
                ],
                [
                    "id" => 135206,
                    "id_entidad" => 210592
                ],
                [
                    "id" => 135207,
                    "id_entidad" => 210593
                ],
                [
                    "id" => 135208,
                    "id_entidad" => 210594
                ],
                [
                    "id" => 135209,
                    "id_entidad" => 210595
                ],
                [
                    "id" => 135210,
                    "id_entidad" => 210596
                ],
                [
                    "id" => 135211,
                    "id_entidad" => 210597
                ],
                [
                    "id" => 135212,
                    "id_entidad" => 210598
                ],
                [
                    "id" => 135213,
                    "id_entidad" => 210599
                ],
                [
                    "id" => 135214,
                    "id_entidad" => 210601
                ],
                [
                    "id" => 135215,
                    "id_entidad" => 210600
                ],
                [
                    "id" => 135216,
                    "id_entidad" => 209254
                ],
                [
                    "id" => 135217,
                    "id_entidad" => 210603
                ],
                [
                    "id" => 135218,
                    "id_entidad" => 210602
                ],
                [
                    "id" => 135219,
                    "id_entidad" => 210604
                ],
                [
                    "id" => 135220,
                    "id_entidad" => 210605
                ],
                [
                    "id" => 135221,
                    "id_entidad" => 210606
                ],
                [
                    "id" => 135222,
                    "id_entidad" => 210607
                ],
                [
                    "id" => 135223,
                    "id_entidad" => 210608
                ],
                [
                    "id" => 135224,
                    "id_entidad" => 210609
                ],
                [
                    "id" => 135225,
                    "id_entidad" => 210610
                ],
                [
                    "id" => 135226,
                    "id_entidad" => 210611
                ],
                [
                    "id" => 135227,
                    "id_entidad" => 207151
                ],
                [
                    "id" => 135228,
                    "id_entidad" => 210612
                ],
                [
                    "id" => 135229,
                    "id_entidad" => 210613
                ],
                [
                    "id" => 135230,
                    "id_entidad" => 210614
                ],
                [
                    "id" => 135231,
                    "id_entidad" => 210615
                ],
                [
                    "id" => 135232,
                    "id_entidad" => 210616
                ],
                [
                    "id" => 135233,
                    "id_entidad" => 210621
                ],
                [
                    "id" => 135234,
                    "id_entidad" => 210617
                ],
                [
                    "id" => 135235,
                    "id_entidad" => 210618
                ],
                [
                    "id" => 135236,
                    "id_entidad" => 210620
                ],
                [
                    "id" => 135237,
                    "id_entidad" => 210619
                ],
                [
                    "id" => 135238,
                    "id_entidad" => 207171
                ],
                [
                    "id" => 135239,
                    "id_entidad" => 210622
                ],
                [
                    "id" => 135240,
                    "id_entidad" => 210623
                ],
                [
                    "id" => 135241,
                    "id_entidad" => 210624
                ],
                [
                    "id" => 135242,
                    "id_entidad" => 210634
                ],
                [
                    "id" => 135243,
                    "id_entidad" => 210635
                ],
                [
                    "id" => 135244,
                    "id_entidad" => 210636
                ],
                [
                    "id" => 135245,
                    "id_entidad" => 210637
                ],
                [
                    "id" => 135246,
                    "id_entidad" => 210638
                ],
                [
                    "id" => 135247,
                    "id_entidad" => 210639
                ],
                [
                    "id" => 135248,
                    "id_entidad" => 210640
                ],
                [
                    "id" => 135249,
                    "id_entidad" => 210642
                ],
                [
                    "id" => 135250,
                    "id_entidad" => 210643
                ],
                [
                    "id" => 135251,
                    "id_entidad" => 210625
                ],
                [
                    "id" => 135252,
                    "id_entidad" => 210628
                ],
                [
                    "id" => 135253,
                    "id_entidad" => 210627
                ],
                [
                    "id" => 135254,
                    "id_entidad" => 210626
                ],
                [
                    "id" => 135255,
                    "id_entidad" => 210644
                ],
                [
                    "id" => 135256,
                    "id_entidad" => 210629
                ],
                [
                    "id" => 135257,
                    "id_entidad" => 210630
                ],
                [
                    "id" => 135258,
                    "id_entidad" => 210632
                ],
                [
                    "id" => 135259,
                    "id_entidad" => 210631
                ],
                [
                    "id" => 135260,
                    "id_entidad" => 210633
                ],
                [
                    "id" => 135261,
                    "id_entidad" => 210641
                ],
                [
                    "id" => 135262,
                    "id_entidad" => 210645
                ],
                [
                    "id" => 135263,
                    "id_entidad" => 210646
                ],
                [
                    "id" => 135264,
                    "id_entidad" => 210647
                ],
                [
                    "id" => 135265,
                    "id_entidad" => 210648
                ],
                [
                    "id" => 135266,
                    "id_entidad" => 210649
                ],
                [
                    "id" => 135267,
                    "id_entidad" => 210650
                ],
                [
                    "id" => 135268,
                    "id_entidad" => 210651
                ],
                [
                    "id" => 135269,
                    "id_entidad" => 210653
                ],
                [
                    "id" => 135270,
                    "id_entidad" => 210652
                ],
                [
                    "id" => 135271,
                    "id_entidad" => null
                ],
                [
                    "id" => 135272,
                    "id_entidad" => 207643
                ],
                [
                    "id" => 135273,
                    "id_entidad" => 210654
                ],
                [
                    "id" => 135274,
                    "id_entidad" => 210656
                ],
                [
                    "id" => 135275,
                    "id_entidad" => 210655
                ],
                [
                    "id" => 135276,
                    "id_entidad" => 210657
                ],
                [
                    "id" => 135277,
                    "id_entidad" => 210659
                ],
                [
                    "id" => 135278,
                    "id_entidad" => 210658
                ],
                [
                    "id" => 135279,
                    "id_entidad" => 210521
                ],
                [
                    "id" => 135280,
                    "id_entidad" => 209091
                ],
                [
                    "id" => 135281,
                    "id_entidad" => 210660
                ],
                [
                    "id" => 135282,
                    "id_entidad" => 208198
                ],
                [
                    "id" => 135283,
                    "id_entidad" => 207939
                ],
                [
                    "id" => 135284,
                    "id_entidad" => 210661
                ],
                [
                    "id" => 135285,
                    "id_entidad" => 210662
                ],
                [
                    "id" => 135286,
                    "id_entidad" => 209392
                ],
                [
                    "id" => 135287,
                    "id_entidad" => 210664
                ],
                [
                    "id" => 135288,
                    "id_entidad" => 210665
                ],
                [
                    "id" => 135289,
                    "id_entidad" => 210666
                ],
                [
                    "id" => 135290,
                    "id_entidad" => 209205
                ],
                [
                    "id" => 135291,
                    "id_entidad" => 209360
                ],
                [
                    "id" => 135292,
                    "id_entidad" => 210667
                ],
                [
                    "id" => 135293,
                    "id_entidad" => 210668
                ],
                [
                    "id" => 135294,
                    "id_entidad" => 210669
                ],
                [
                    "id" => 135295,
                    "id_entidad" => 210670
                ],
                [
                    "id" => 135296,
                    "id_entidad" => 210671
                ],
                [
                    "id" => 135297,
                    "id_entidad" => 210672
                ],
                [
                    "id" => 135298,
                    "id_entidad" => 208449
                ],
                [
                    "id" => 135299,
                    "id_entidad" => 210673
                ],
                [
                    "id" => 135300,
                    "id_entidad" => 210675
                ],
                [
                    "id" => 135301,
                    "id_entidad" => 210676
                ],
                [
                    "id" => 135302,
                    "id_entidad" => 210677
                ],
                [
                    "id" => 135303,
                    "id_entidad" => 207298
                ],
                [
                    "id" => 135304,
                    "id_entidad" => 210678
                ],
                [
                    "id" => 135305,
                    "id_entidad" => 210679
                ],
                [
                    "id" => 135306,
                    "id_entidad" => 210680
                ],
                [
                    "id" => 135307,
                    "id_entidad" => 210681
                ],
                [
                    "id" => 135308,
                    "id_entidad" => 210682
                ],
                [
                    "id" => 135309,
                    "id_entidad" => 210683
                ],
                [
                    "id" => 135310,
                    "id_entidad" => 210684
                ],
                [
                    "id" => 135311,
                    "id_entidad" => 210685
                ],
                [
                    "id" => 135312,
                    "id_entidad" => 210686
                ],
                [
                    "id" => 135313,
                    "id_entidad" => 208261
                ],
                [
                    "id" => 135314,
                    "id_entidad" => 210687
                ],
                [
                    "id" => 135315,
                    "id_entidad" => 210688
                ],
                [
                    "id" => 135316,
                    "id_entidad" => 210688
                ],
                [
                    "id" => 135317,
                    "id_entidad" => 208462
                ],
                [
                    "id" => 135318,
                    "id_entidad" => 210688
                ],
                [
                    "id" => 135319,
                    "id_entidad" => 210689
                ],
                [
                    "id" => 135320,
                    "id_entidad" => 210690
                ],
                [
                    "id" => 135321,
                    "id_entidad" => 210691
                ],
                [
                    "id" => 135322,
                    "id_entidad" => 210692
                ],
                [
                    "id" => 135323,
                    "id_entidad" => 210693
                ],
                [
                    "id" => 135324,
                    "id_entidad" => 210694
                ],
                [
                    "id" => 135325,
                    "id_entidad" => 210695
                ],
                [
                    "id" => 135326,
                    "id_entidad" => 210696
                ],
                [
                    "id" => 135327,
                    "id_entidad" => 210698
                ],
                [
                    "id" => 135328,
                    "id_entidad" => 210697
                ],
                [
                    "id" => 135329,
                    "id_entidad" => 210699
                ],
                [
                    "id" => 135330,
                    "id_entidad" => 210701
                ],
                [
                    "id" => 135331,
                    "id_entidad" => 210700
                ],
                [
                    "id" => 135332,
                    "id_entidad" => 207131
                ],
                [
                    "id" => 135333,
                    "id_entidad" => 210702
                ],
                [
                    "id" => 135334,
                    "id_entidad" => 210703
                ],
                [
                    "id" => 135335,
                    "id_entidad" => 210704
                ],
                [
                    "id" => 135336,
                    "id_entidad" => 210705
                ],
                [
                    "id" => 135337,
                    "id_entidad" => 208278
                ],
                [
                    "id" => 135338,
                    "id_entidad" => 210707
                ],
                [
                    "id" => 135339,
                    "id_entidad" => 210708
                ],
                [
                    "id" => 135340,
                    "id_entidad" => 210709
                ],
                [
                    "id" => 135341,
                    "id_entidad" => null
                ],
                [
                    "id" => 135342,
                    "id_entidad" => null
                ],
                [
                    "id" => 135343,
                    "id_entidad" => 210710
                ],
                [
                    "id" => 135344,
                    "id_entidad" => 210711
                ],
                [
                    "id" => 135345,
                    "id_entidad" => 210712
                ],
                [
                    "id" => 135346,
                    "id_entidad" => 210713
                ],
                [
                    "id" => 135347,
                    "id_entidad" => 210714
                ],
                [
                    "id" => 135348,
                    "id_entidad" => 210715
                ],
                [
                    "id" => 135349,
                    "id_entidad" => 210716
                ],
                [
                    "id" => 135350,
                    "id_entidad" => 210717
                ],
                [
                    "id" => 135351,
                    "id_entidad" => 210718
                ],
                [
                    "id" => 135352,
                    "id_entidad" => 210719
                ],
                [
                    "id" => 135353,
                    "id_entidad" => 210720
                ],
                [
                    "id" => 135354,
                    "id_entidad" => 210721
                ],
                [
                    "id" => 135355,
                    "id_entidad" => 210722
                ],
                [
                    "id" => 135356,
                    "id_entidad" => 210723
                ],
                [
                    "id" => 135357,
                    "id_entidad" => 210724
                ],
                [
                    "id" => 135358,
                    "id_entidad" => 210725
                ],
                [
                    "id" => 135359,
                    "id_entidad" => null
                ],
                [
                    "id" => 135360,
                    "id_entidad" => 210726
                ],
                [
                    "id" => 135361,
                    "id_entidad" => 210728
                ],
                [
                    "id" => 135362,
                    "id_entidad" => 210727
                ],
                [
                    "id" => 135363,
                    "id_entidad" => 210729
                ],
                [
                    "id" => 135364,
                    "id_entidad" => 207059
                ],
                [
                    "id" => 135365,
                    "id_entidad" => 210730
                ],
                [
                    "id" => 135366,
                    "id_entidad" => 210731
                ],
                [
                    "id" => 135367,
                    "id_entidad" => 210732
                ],
                [
                    "id" => 135368,
                    "id_entidad" => 210734
                ],
                [
                    "id" => 135369,
                    "id_entidad" => 210733
                ],
                [
                    "id" => 135370,
                    "id_entidad" => 208948
                ],
                [
                    "id" => 135371,
                    "id_entidad" => 210735
                ],
                [
                    "id" => 135372,
                    "id_entidad" => 210736
                ],
                [
                    "id" => 135373,
                    "id_entidad" => 210737
                ],
                [
                    "id" => 135374,
                    "id_entidad" => 210738
                ],
                [
                    "id" => 135375,
                    "id_entidad" => 210739
                ],
                [
                    "id" => 135376,
                    "id_entidad" => 210740
                ],
                [
                    "id" => 135377,
                    "id_entidad" => 210741
                ],
                [
                    "id" => 135378,
                    "id_entidad" => 208480
                ],
                [
                    "id" => 135379,
                    "id_entidad" => 210742
                ],
                [
                    "id" => 135380,
                    "id_entidad" => 210743
                ],
                [
                    "id" => 135381,
                    "id_entidad" => 210745
                ],
                [
                    "id" => 135382,
                    "id_entidad" => 210746
                ],
                [
                    "id" => 135383,
                    "id_entidad" => 210747
                ],
                [
                    "id" => 135384,
                    "id_entidad" => 210748
                ],
                [
                    "id" => 135385,
                    "id_entidad" => 210749
                ],
                [
                    "id" => 135386,
                    "id_entidad" => 210751
                ],
                [
                    "id" => 135387,
                    "id_entidad" => 210752
                ],
                [
                    "id" => 135388,
                    "id_entidad" => 210753
                ],
                [
                    "id" => 135389,
                    "id_entidad" => 210754
                ],
                [
                    "id" => 135390,
                    "id_entidad" => 210755
                ],
                [
                    "id" => 135391,
                    "id_entidad" => 210756
                ],
                [
                    "id" => 135392,
                    "id_entidad" => 210757
                ],
                [
                    "id" => 135393,
                    "id_entidad" => 210758
                ],
                [
                    "id" => 135394,
                    "id_entidad" => 210760
                ],
                [
                    "id" => 135395,
                    "id_entidad" => 210761
                ],
                [
                    "id" => 135396,
                    "id_entidad" => 210762
                ],
                [
                    "id" => 135397,
                    "id_entidad" => 210763
                ],
                [
                    "id" => 135398,
                    "id_entidad" => 210764
                ],
                [
                    "id" => 135399,
                    "id_entidad" => 210765
                ],
                [
                    "id" => 135400,
                    "id_entidad" => 210766
                ],
                [
                    "id" => 135401,
                    "id_entidad" => 210767
                ],
                [
                    "id" => 135402,
                    "id_entidad" => 210768
                ],
                [
                    "id" => 135403,
                    "id_entidad" => 210769
                ],
                [
                    "id" => 135404,
                    "id_entidad" => 210770
                ],
                [
                    "id" => 135405,
                    "id_entidad" => 210771
                ],
                [
                    "id" => 135406,
                    "id_entidad" => 210772
                ],
                [
                    "id" => 135407,
                    "id_entidad" => 210773
                ],
                [
                    "id" => 135408,
                    "id_entidad" => 210774
                ],
                [
                    "id" => 135409,
                    "id_entidad" => 210775
                ],
                [
                    "id" => 135410,
                    "id_entidad" => 210776
                ],
                [
                    "id" => 135411,
                    "id_entidad" => 210777
                ],
                [
                    "id" => 135412,
                    "id_entidad" => 210779
                ],
                [
                    "id" => 135413,
                    "id_entidad" => 210780
                ],
                [
                    "id" => 135414,
                    "id_entidad" => 207237
                ],
                [
                    "id" => 135415,
                    "id_entidad" => 208821
                ],
                [
                    "id" => 135416,
                    "id_entidad" => 210781
                ],
                [
                    "id" => 135417,
                    "id_entidad" => 210782
                ],
                [
                    "id" => 135418,
                    "id_entidad" => 210783
                ],
                [
                    "id" => 135419,
                    "id_entidad" => 210785
                ],
                [
                    "id" => 135420,
                    "id_entidad" => 207273
                ],
                [
                    "id" => 135421,
                    "id_entidad" => 210786
                ],
                [
                    "id" => 135422,
                    "id_entidad" => 209973
                ],
                [
                    "id" => 135423,
                    "id_entidad" => 210787
                ],
                [
                    "id" => 135424,
                    "id_entidad" => 210784
                ],
                [
                    "id" => 135425,
                    "id_entidad" => 210788
                ],
                [
                    "id" => 135426,
                    "id_entidad" => 210789
                ],
                [
                    "id" => 135427,
                    "id_entidad" => 210790
                ],
                [
                    "id" => 135428,
                    "id_entidad" => 210484
                ],
                [
                    "id" => 135429,
                    "id_entidad" => 209108
                ],
                [
                    "id" => 135430,
                    "id_entidad" => 210791
                ],
                [
                    "id" => 135431,
                    "id_entidad" => 210792
                ],
                [
                    "id" => 135432,
                    "id_entidad" => 210570
                ],
                [
                    "id" => 135433,
                    "id_entidad" => 210793
                ],
                [
                    "id" => 135434,
                    "id_entidad" => 210794
                ],
                [
                    "id" => 135435,
                    "id_entidad" => 210810
                ],
                [
                    "id" => 135436,
                    "id_entidad" => 210813
                ],
                [
                    "id" => 135437,
                    "id_entidad" => 210809
                ],
                [
                    "id" => 135438,
                    "id_entidad" => 210796
                ],
                [
                    "id" => 135439,
                    "id_entidad" => 210797
                ],
                [
                    "id" => 135440,
                    "id_entidad" => 210802
                ],
                [
                    "id" => 135441,
                    "id_entidad" => 210800
                ],
                [
                    "id" => 135442,
                    "id_entidad" => 210801
                ],
                [
                    "id" => 135443,
                    "id_entidad" => 210795
                ],
                [
                    "id" => 135444,
                    "id_entidad" => 210814
                ],
                [
                    "id" => 135445,
                    "id_entidad" => 210798
                ],
                [
                    "id" => 135446,
                    "id_entidad" => 210799
                ],
                [
                    "id" => 135447,
                    "id_entidad" => 210803
                ],
                [
                    "id" => 135448,
                    "id_entidad" => 210804
                ],
                [
                    "id" => 135449,
                    "id_entidad" => 210805
                ],
                [
                    "id" => 135450,
                    "id_entidad" => 210806
                ],
                [
                    "id" => 135451,
                    "id_entidad" => 210807
                ],
                [
                    "id" => 135452,
                    "id_entidad" => 210812
                ],
                [
                    "id" => 135453,
                    "id_entidad" => 210815
                ],
                [
                    "id" => 135454,
                    "id_entidad" => 210816
                ],
                [
                    "id" => 135455,
                    "id_entidad" => 210817
                ],
                [
                    "id" => 135456,
                    "id_entidad" => 210818
                ],
                [
                    "id" => 135457,
                    "id_entidad" => 210819
                ],
                [
                    "id" => 135458,
                    "id_entidad" => 210820
                ],
                [
                    "id" => 135459,
                    "id_entidad" => 210821
                ],
                [
                    "id" => 135460,
                    "id_entidad" => 210822
                ],
                [
                    "id" => 135461,
                    "id_entidad" => 210823
                ],
                [
                    "id" => 135462,
                    "id_entidad" => 207333
                ],
                [
                    "id" => 135463,
                    "id_entidad" => 210824
                ],
                [
                    "id" => 135464,
                    "id_entidad" => 210825
                ],
                [
                    "id" => 135465,
                    "id_entidad" => 209369
                ],
                [
                    "id" => 135466,
                    "id_entidad" => 210473
                ],
                [
                    "id" => 135467,
                    "id_entidad" => 210826
                ],
                [
                    "id" => 135468,
                    "id_entidad" => 210827
                ],
                [
                    "id" => 135469,
                    "id_entidad" => 210828
                ],
                [
                    "id" => 135470,
                    "id_entidad" => 210829
                ],
                [
                    "id" => 135471,
                    "id_entidad" => 210830
                ],
                [
                    "id" => 135472,
                    "id_entidad" => 210831
                ],
                [
                    "id" => 135473,
                    "id_entidad" => 210832
                ],
                [
                    "id" => 135474,
                    "id_entidad" => 210834
                ],
                [
                    "id" => 135475,
                    "id_entidad" => 210835
                ],
                [
                    "id" => 135476,
                    "id_entidad" => 210836
                ],
                [
                    "id" => 135477,
                    "id_entidad" => 210837
                ],
                [
                    "id" => 135478,
                    "id_entidad" => 207937
                ],
                [
                    "id" => 135479,
                    "id_entidad" => 210838
                ],
                [
                    "id" => 135480,
                    "id_entidad" => 207373
                ],
                [
                    "id" => 135481,
                    "id_entidad" => 210840
                ],
                [
                    "id" => 135482,
                    "id_entidad" => 210841
                ],
                [
                    "id" => 135483,
                    "id_entidad" => 210842
                ],
                [
                    "id" => 135484,
                    "id_entidad" => 210843
                ],
                [
                    "id" => 135485,
                    "id_entidad" => 210844
                ],
                [
                    "id" => 135486,
                    "id_entidad" => 210845
                ],
                [
                    "id" => 135487,
                    "id_entidad" => 210846
                ],
                [
                    "id" => 135488,
                    "id_entidad" => 210847
                ],
                [
                    "id" => 135489,
                    "id_entidad" => 210848
                ],
                [
                    "id" => 135490,
                    "id_entidad" => 210849
                ],
                [
                    "id" => 135491,
                    "id_entidad" => 210850
                ],
                [
                    "id" => 135492,
                    "id_entidad" => 207213
                ],
                [
                    "id" => 135493,
                    "id_entidad" => 210851
                ],
                [
                    "id" => 135494,
                    "id_entidad" => 210852
                ],
                [
                    "id" => 135495,
                    "id_entidad" => 210853
                ],
                [
                    "id" => 135496,
                    "id_entidad" => 210854
                ],
                [
                    "id" => 135497,
                    "id_entidad" => 210855
                ],
                [
                    "id" => 135498,
                    "id_entidad" => 210856
                ],
                [
                    "id" => 135499,
                    "id_entidad" => 210857
                ],
                [
                    "id" => 135500,
                    "id_entidad" => 210858
                ],
                [
                    "id" => 135501,
                    "id_entidad" => 210859
                ],
                [
                    "id" => 135502,
                    "id_entidad" => 210860
                ],
                [
                    "id" => 135503,
                    "id_entidad" => 210861
                ],
                [
                    "id" => 135504,
                    "id_entidad" => 210862
                ],
                [
                    "id" => 135505,
                    "id_entidad" => 210863
                ],
                [
                    "id" => 135506,
                    "id_entidad" => 210864
                ],
                [
                    "id" => 135507,
                    "id_entidad" => 210865
                ],
                [
                    "id" => 135508,
                    "id_entidad" => 210866
                ],
                [
                    "id" => 135509,
                    "id_entidad" => 210867
                ],
                [
                    "id" => 135510,
                    "id_entidad" => 210868
                ],
                [
                    "id" => 135511,
                    "id_entidad" => 210870
                ],
                [
                    "id" => 135512,
                    "id_entidad" => 210871
                ],
                [
                    "id" => 135513,
                    "id_entidad" => 210872
                ],
                [
                    "id" => 135514,
                    "id_entidad" => 210873
                ],
                [
                    "id" => 135515,
                    "id_entidad" => 210874
                ],
                [
                    "id" => 135516,
                    "id_entidad" => 210875
                ],
                [
                    "id" => 135517,
                    "id_entidad" => 210877
                ],
                [
                    "id" => 135518,
                    "id_entidad" => 210878
                ],
                [
                    "id" => 135519,
                    "id_entidad" => 210879
                ],
                [
                    "id" => 135520,
                    "id_entidad" => 210882
                ],
                [
                    "id" => 135521,
                    "id_entidad" => 210884
                ],
                [
                    "id" => 135522,
                    "id_entidad" => 210885
                ],
                [
                    "id" => 135523,
                    "id_entidad" => 210886
                ],
                [
                    "id" => 135524,
                    "id_entidad" => 210887
                ],
                [
                    "id" => 135525,
                    "id_entidad" => 210315
                ],
                [
                    "id" => 135526,
                    "id_entidad" => 210889
                ],
                [
                    "id" => 135527,
                    "id_entidad" => 210890
                ],
                [
                    "id" => 135528,
                    "id_entidad" => 210891
                ],
                [
                    "id" => 135529,
                    "id_entidad" => 210892
                ],
                [
                    "id" => 135530,
                    "id_entidad" => 210893
                ],
                [
                    "id" => 135531,
                    "id_entidad" => 210894
                ],
                [
                    "id" => 135532,
                    "id_entidad" => 210895
                ],
                [
                    "id" => 135533,
                    "id_entidad" => 210896
                ],
                [
                    "id" => 135534,
                    "id_entidad" => 209565
                ],
                [
                    "id" => 135535,
                    "id_entidad" => 210897
                ],
                [
                    "id" => 135536,
                    "id_entidad" => 210899
                ],
                [
                    "id" => 135537,
                    "id_entidad" => 210900
                ],
                [
                    "id" => 135538,
                    "id_entidad" => 210901
                ],
                [
                    "id" => 135539,
                    "id_entidad" => 210902
                ],
                [
                    "id" => 135540,
                    "id_entidad" => 210903
                ],
                [
                    "id" => 135541,
                    "id_entidad" => 210904
                ],
                [
                    "id" => 135542,
                    "id_entidad" => 207685
                ],
                [
                    "id" => 135543,
                    "id_entidad" => 210905
                ],
                [
                    "id" => 135544,
                    "id_entidad" => 210906
                ],
                [
                    "id" => 135545,
                    "id_entidad" => 210907
                ],
                [
                    "id" => 135546,
                    "id_entidad" => 210908
                ],
                [
                    "id" => 135547,
                    "id_entidad" => 210909
                ],
                [
                    "id" => 135548,
                    "id_entidad" => 210910
                ],
                [
                    "id" => 135549,
                    "id_entidad" => 210911
                ],
                [
                    "id" => 135550,
                    "id_entidad" => 210912
                ],
                [
                    "id" => 135551,
                    "id_entidad" => 207682
                ],
                [
                    "id" => 135552,
                    "id_entidad" => 210913
                ],
                [
                    "id" => 135553,
                    "id_entidad" => 210915
                ],
                [
                    "id" => 135554,
                    "id_entidad" => 210914
                ],
                [
                    "id" => 135555,
                    "id_entidad" => 210916
                ],
                [
                    "id" => 135556,
                    "id_entidad" => 210917
                ],
                [
                    "id" => 135557,
                    "id_entidad" => 210918
                ],
                [
                    "id" => 135558,
                    "id_entidad" => 210920
                ],
                [
                    "id" => 135559,
                    "id_entidad" => 210921
                ],
                [
                    "id" => 135560,
                    "id_entidad" => 210922
                ],
                [
                    "id" => 135561,
                    "id_entidad" => 210923
                ],
                [
                    "id" => 135562,
                    "id_entidad" => 210924
                ],
                [
                    "id" => 135563,
                    "id_entidad" => 210925
                ],
                [
                    "id" => 135564,
                    "id_entidad" => 210926
                ],
                [
                    "id" => 135565,
                    "id_entidad" => 210927
                ],
                [
                    "id" => 135566,
                    "id_entidad" => 208807
                ],
                [
                    "id" => 135567,
                    "id_entidad" => 210928
                ],
                [
                    "id" => 135568,
                    "id_entidad" => 210929
                ],
                [
                    "id" => 135569,
                    "id_entidad" => 210930
                ],
                [
                    "id" => 135570,
                    "id_entidad" => 210931
                ],
                [
                    "id" => 135571,
                    "id_entidad" => 210932
                ],
                [
                    "id" => 135572,
                    "id_entidad" => 210933
                ],
                [
                    "id" => 135573,
                    "id_entidad" => 210934
                ],
                [
                    "id" => 135574,
                    "id_entidad" => 210935
                ],
                [
                    "id" => 135575,
                    "id_entidad" => 210937
                ],
                [
                    "id" => 135576,
                    "id_entidad" => 210936
                ],
                [
                    "id" => 135577,
                    "id_entidad" => 210938
                ],
                [
                    "id" => 135578,
                    "id_entidad" => 210940
                ],
                [
                    "id" => 135579,
                    "id_entidad" => 210941
                ],
                [
                    "id" => 135580,
                    "id_entidad" => 210942
                ],
                [
                    "id" => 135581,
                    "id_entidad" => 210943
                ],
                [
                    "id" => 135582,
                    "id_entidad" => 210945
                ],
                [
                    "id" => 135583,
                    "id_entidad" => 210946
                ],
                [
                    "id" => 135584,
                    "id_entidad" => 210944
                ],
                [
                    "id" => 135585,
                    "id_entidad" => 210947
                ],
                [
                    "id" => 135586,
                    "id_entidad" => 210948
                ],
                [
                    "id" => 135587,
                    "id_entidad" => 210949
                ],
                [
                    "id" => 135588,
                    "id_entidad" => 210951
                ]
            ];

            // Ruta relativa a la carpeta desde storage
            $directory = 'public/empresa/logo';

            // Obtener todos los archivos en la carpeta
            $files = Storage::files($directory);

            // Filtrar los archivos y extraer solo los nombres numéricos
            $numeros = array_map(function ($file) {
                // Extraer solo el nombre del archivo sin la extensión
                return pathinfo($file, PATHINFO_FILENAME);
            }, array_filter($files, function ($file) {
                // Extraer solo el nombre del archivo sin la extensión
                $fileName = pathinfo($file, PATHINFO_FILENAME);

                // Verificar si el nombre es solo números
                return ctype_digit($fileName);
            }));

            $noEmpresa = [];

            foreach ($numeros as $key => $value) {
                foreach ($entidades as $entidad) {
                    if ($entidad["id"] == (int) $value) {
                        if ($entidad["id_entidad"] && $entidad["id_entidad"] >= 210884) {
                            $empresa = \App\Models\Empresa::where('id_viejo', '=', $entidad["id_entidad"])->first();
                            if ($empresa) {

                                $newLogo = new \App\Models\EmpresaLogo();
                                $newLogo->id_empresa = $empresa->id;
                                $newLogo->name = $entidad["id_entidad"];
                                $newLogo->path = $entidad["id"];
                                $newLogo->save();
                            } else {
                                $noEmpresa[] = $entidad["id_entidad"];
                            }
                        }
                        break;
                    }
                }
            }

            // Retornar solo los nombres numéricos en un array
            return $noEmpresa;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    static public function generarStringAleatorio()
    {
        // Array de palabras
        $palabras = [
            "Manzana",
            "Computadora",
            "Hamburguesa",
            "Baloncesto",
            "Elefante",
            "Guitarra",
            "Refrigerador",
            "Zanahoria",
            "Aventurero",
            "Jirafa",
            "Banquero",
            "Helicoptero",
            "Orangutan",
            "Xilofono",
            "Dinosaurio",
            "Paracaidas",
            "Chocolate",
            "Nadar",
            "Fotografia",
            "Telescopio",
            "Cocodrilo",
            "Correr",
            "Esquiar",
            "Patinar",
            "Canario",
            "Perseverante",
            "Matematicas",
            "Ardilla",
            "Piramide",
            "Remolacha",
            "Biblioteca",
            "Rinoceronte",
            "Expedicion",
            "Zapatilla",
            "Kangaroo",
            "Paraguas",
            "Vibrante",
            "Diamante",
            "Camaleon",
            "Construccion",
            "Escaparate",
            "Necesidad",
            "Malabarista",
            "Ventilador",
            "Desarrollo",
            "Ingenioso",
            "Tsunami",
            "Jupiter",
            "Montañismo",
            "Ratón",
            "Nebulosa",
            "Golondrina",
            "Tornado",
            "Zanjar",
            "Langosta",
            "Desafiante",
            "Aerodinamico",
            "Mandolina",
            "Saxofon",
            "Desodorante",
            "Platano",
            "Mariposa",
            "Trineo",
            "Zarigüeya",
            "Hipopotamo",
            "Mamut",
            "Embarcacion",
            "Astronauta",
            "Furgoneta",
            "Cientifico",
            "Aviador",
            "Estetoscopio",
            "Baliza",
            "Arquitecto",
            "Rompecabezas",
            "Intrépido",
            "Búfalo",
            "Renacuajo",
            "Velociraptor",
            "Obstaculo",
            "Catarata",
            "Espectaculo",
            "Armadillo",
            "Cangrejo",
            "Espirituoso",
            "Reconocimiento",
            "Serenidad",
            "Melodioso",
            "Taquigrafo",
            "Amplificador",
            "Fosforo",
            "Crepusculo",
            "Ventana",
            "Cirugia",
            "Malabarismo",
            "Monumento",
            "Domador",
            "Despiadado",
            "Fenomeno",
            "Zumbido",
            "Narciso",
            "Tintineo",
            "Desinfectante",
            "Telesilla",
            "Náufrago",
            "Carrusel",
            "Intermitente",
            "Pantera",
            "Desesperado",
            "Jabonera",
            "Velero",
            "Espectacular",
            "Apasionado",
            "Elastico",
            "Nanotecnologia",
            "Insaciable",
            "Faraon",
            "Magnifico",
            "Peregrino",
            "Gravitacion",
            "Ostentoso",
            "Trituradora",
            "Tormenta",
            "Ciclista",
            "Distincion",
            "Inquebrantable",
            "Perspicaz",
            "Estallido",
            "Taquilla",
            "Deslizamiento",
            "Melocoton",
            "Obstetricia",
            "Manantial",
            "Resplandor",
            "Helio",
            "Zorro",
            "Entrelazado",
            "Necesario",
            "Portavoz",
            "Engreido",
            "Tramposo",
            "Autodidacta",
            "Juglar",
            "Impetuoso",
            "Susurro",
            "Caprichoso",
            "Cautivador",
            "Desabrido",
            "Desalmado",
            "Misterio",
            "Albaricoque",
            "Zarandeo",
            "Filigrana",
            "Mangosta",
            "Hernia",
            "Zurullo",
            "Ascensor",
            "Amperímetro",
            "Paquidermo",
            "Zafiro",
            "Inmunidad",
            "Fenotipo",
            "Entumecido",
            "Destilador",
            "Mampara",
            "Laberinto",
            "Alambique",
            "Apisonadora",
            "Resplandeciente",
            "Astrofisico",
            "Deshuesado",
            "Superviviente",
            "Aventura",
            "Encuentro",
            "Resistencia",
            "Trepidante",
            "Agitador",
            "Equilibrio",
            "Emprendedor",
            "Misterioso",
            "Renovación",
            "Pirueta",
            "Fantastico",
            "Libertad",
            "Prestigio",
            "Despliegue",
            "Habilidad",
            "Rutina",
            "Desafío",
            "Destreza",
            "Intriga",
            "Revelación"
        ];

        $palabraAleatoria = $palabras[array_rand($palabras)];

        // Definir contador para llevar la cuenta de los números agregados
        $numerosAgregados = 0;

        // Verificar si la palabra seleccionada tiene menos de 10 caracteres
        while (strlen($palabraAleatoria) < 10 || $numerosAgregados < 2) {
            // Calcular cuántos números adicionales se necesitan
            $numCaracteresFaltantes = 10 - strlen($palabraAleatoria) + $numerosAgregados;

            // Generar números aleatorios adicionales
            $numero = rand(0, 9);

            // Concatenar números adicionales a la palabra
            $palabraAleatoria .= $numero;

            // Actualizar el contador de números agregados
            $numerosAgregados += 1;
        }

        // Agregar un signo de exclamación al final
        $palabraConNumeros = $palabraAleatoria . '!';

        return $palabraConNumeros;
    }

    static public function searchEmpresa(Request $request)
    {

        try {
            //dd($request->search);

            $result = \App\Models\Empresa::leftJoin('expositor', function ($query) use ($request) {
                $query->on('expositor.id_empresa', '=', 'empresa.id');
                $query->where('expositor.id_exposicion', '=', $request->idExposicion);
                $query->where('expositor.deleted_at', '=', null);

                if (Auth::guard()->user()->grupo == 3 && $request->asignacion == 'true') {
                    $query->where('expositor.pertenece_etif', '=', true)->where('aprobado', '=', true);
                }
            })
                ->select(
                    'empresa.id',
                    'empresa.cuit',
                    'empresa.razon_social',
                    'empresa.nombre_fantasia',
                    'expositor.usuario',
                    'expositor.whatsapp',
                )
                ->where(function ($query) use ($request) {
                    $query->where(DB::raw('LOWER(empresa.cuit)'), 'like', '%' . strtolower($request->search) . '%');
                    $query->orWhere(DB::raw('LOWER(empresa.razon_social)'), 'like', '%' . strtolower($request->search) . '%');
                    $query->orWhere(DB::raw('LOWER(empresa.nombre_fantasia)'), 'like', '%' . strtolower($request->search) . '%');
                });

            if (Auth::guard()->user()->grupo == 3 && $request->asignacion == 'true') {
                $result = $result->where('empresa.pertenece_etif', '=', true);
            }

            $result = $result->get()->toArray();

            return response()->json(["items" => $result]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    static public function updateStandValue(Request $request)
    {
        try {
            $stand = \App\Models\Stand::find($request->id);
            $stand->valor = $request->value;
            $stand->save();

            return response()->json([
                'sumValue' => \App\Models\Stand::where('id_exposicion', '=', $stand->id_exposicion)->sum('valor'),
            ], 200);
        } catch (\Exception $ex) {
            Log::error('Error capturado: ' . $ex->getMessage());
            return response()->json([
                'message' => 'Ocurrió un error en el servidor.',
                'error' => $ex->getMessage()
            ], 500);
        }
    }

    static public function variableUpdateStore(Request $request)
    {
        try {

            // Create a new MessageBag instance flashed to the session.
            $messageBag = session()->get('messageBag') ?? new MessageBag;
            request()->session()->flash('messageBag', $messageBag);

            $item = null;
            if ($request->idVariable) {
                $item = \App\Models\Variables::find($request->idVariable);
            } else {
                $item = new \App\Models\Variables();
            }

            if ($request->id_exposicion) $item->id_exposicion = $request->id_exposicion;
            if ($request->descripcion) $item->descripcion = $request->descripcion;
            if ($request->value_string) $item->value_string = $request->value_string;
            if ($request->value_integer) $item->value_integer = $request->value_integer;
            if ($request->value_date) $item->value_date = $request->value_date;
            $item->save();

            $messageBag->add('success', 'Variable ' . $item->descripcion . ' actualizada con éxito.');

            return back();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function matchExpositorEtifEmpresa()
    {
        try {

            $expositores = \App\Models\Expositor::where('pertenece_etif', '=', true)->get();

            foreach ($expositores as $key => $expositor) {
                if ($expositor->id_empresa) {
                    $empresa = \App\Models\Empresa::find($expositor->id_empresa);
                    $empresa->pertenece_etif = true;
                    $empresa->save();
                }
            }

            return response()->json([], 200);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
