var ORDER_AJAX_DELIVERY_MAP = {
    DELIVERY_TARIFF: 40, // рублей за доп киллометр
    CITY_COST: 500, // цена по городу
    WAY_OUTSIDE_COST: 500, // цена за городом
    MINIMUM_OUTSIDE: 0, // количество киллометров включенных за городом
    MINIMUM_COST: 500, // минимальная цена
    MAP_CENTER: [54.965217, 39.025394],
    MAP_ZOOM: 11,
    MAP_FROM: "г Луховицы",
    MAP_POLYGON: [
        [54.95314134267285,39.001216239701016],
        [54.95217826971097,39.000781535634594],
        [54.95233297706876,38.99891449833798],
        [54.95194429897323,38.9975624451714],
        [54.950212996024014,38.99698680306518],
        [54.94940452217263,39.00447183646284],
        [54.94868818538494,39.00719245681162],
        [54.94285718135097,39.002959715484366],
        [54.938516236164865,39.00118915319669],
        [54.935830385997235,39.00316295469346],
        [54.934095646135546,38.998463420162416],
        [54.932202133832575,38.99737965555027],
        [54.93176392574576,38.9994888357138],
        [54.93143052641992,38.999400804755766],
        [54.93112183665861,38.999302044960984],
        [54.93092424410429,38.9992017030932],
        [54.9306957598482,38.99909063238814],
        [54.93054450151463,38.99918340956882],
        [54.93041177719617,38.99933519534756],
        [54.930297533764325,38.999471437940024],
        [54.93021109202655,38.99955403635166],
        [54.92999333570849,38.9998622153544],
        [54.929882912532065,39.00006994903623],
        [54.92982809364328,39.000277682717886],
        [54.9295377665991,39.00156459297787],
        [54.92858341685855,39.00093475579838],
        [54.92755376946099,39.000362959625505],
        [54.92665076063934,38.99858953381293],
        [54.926956149639366,38.99800054610737],
        [54.9275970725295,38.9988081655903],
        [54.92813377976053,38.99822492241441],
        [54.92836526706278,38.99745683912888],
        [54.92866945372465,38.996879678437416],
        [54.92891803231266,38.99683895954791],
        [54.929120115870916,38.99651319787355],
        [54.92913705459774,38.99588800029596],
        [54.92904858741285,38.995710121791404],
        [54.92877575193028,38.996050335982495],
        [54.92830520252021,38.995253293549446],
        [54.928730159945104,38.99375022031217],
        [54.92823934320309,38.99310441777316],
        [54.926974965532985,38.99145689382067],
        [54.92658862375139,38.99179472263315],
        [54.925732683483254,38.990909464134475],
        [54.92515478105435,38.992298718880996],
        [54.924556843217516,38.99368569551098],
        [54.92402068828726,38.99484736658432],
        [54.92487313324872,38.99657670239659],
        [54.924013877829616,38.997708111525576],
        [54.922852060431296,39.00073391786405],
        [54.92272841567643,39.002166354762764],
        [54.92342038524568,39.00232219745424],
        [54.92170075203286,39.01097943168859],
        [54.92018920290378,39.011234882982805],
        [54.920114797502954,39.01186407317111],
        [54.920166212953795,39.012331295299056],
        [54.92013463370432,39.012916166000366],
        [54.920140133109726,39.01325427347239],
        [54.92027661234509,39.01391501390607],
        [54.919575505091395,39.013879008870475],
        [54.915666625526384,39.01247794113317],
        [54.91511972574626,39.010476058575684],
        [54.90974861703464,39.00728768500301],
        [54.900837242305236,39.04928772905313],
        [54.9088280895271,39.05450923790774],
        [54.90905414785795,39.05760107280416],
        [54.912863607415545,39.05960262167897],
        [54.91570928065135,39.05719444856791],
        [54.91652712342158,39.05629949192564],
        [54.91666746080996,39.05533143976313],
        [54.9168603301535,39.054363387601136],
        [54.91704083805646,39.05347580170984],
        [54.91725224627771,39.05270086859608],
        [54.91741520616573,39.0517899606272],
        [54.91747505521066,39.051149434220754],
        [54.917609066251664,39.050530365486445],
        [54.9179157127494,39.04914738873121],
        [54.91809839056363,39.04843176047278],
        [54.91828724761886,39.047673216870066],
        [54.91937597734916,39.048301187235374],
        [54.919514450079696,39.048340540498],
        [54.91959112377102,39.048304791908095],
        [54.92071778339931,39.0473320724997],
        [54.92512215157895,39.0499911492801],
        [54.92486561505781,39.05109875185069],
        [54.925241146262834,39.05144347995132],
        [54.926395211860154,39.05213153080602],
        [54.92770432645627,39.05301146697857],
        [54.92845463448715,39.05345143506479],
        [54.92912460945667,39.05388067431493],
        [54.93043903993308,39.05469287139536],
        [54.93207622639018,39.0557376598278],
        [54.932700756522244,39.05609367158683],
        [54.93334844296837,39.05654080418897],
        [54.93477589332546,39.057426876651036],
        [54.935827602166285,39.0580593400161],
        [54.93687310619107,39.058745447561485],
        [54.93831545540119,39.05961072030106],
        [54.939770106151514,39.060540366057],
        [54.942276806742534,39.06211190357328],
        [54.9437905496369,39.063090758603074],
        [54.94243261477218,39.06553324398278],
        [54.9428225585647,39.066296666519605],
        [54.94231343062004,39.06720945517172],
        [54.94292221529958,39.06804177755473],
        [54.9506179345735,39.053795558443326],
        [54.95120905685669,39.05467217567589],
        [54.951787820641854,39.05552733523638],
        [54.95230453068033,39.056234956697246],
        [54.95263509093938,39.056635593957765],
        [54.952965648472755,39.05706841772652],
        [54.95333170756232,39.05745027952389],
        [54.95372246169312,39.05783214132132],
        [54.954248508854945,39.058323270913135],
        [54.954774549105665,39.05883585817702],
        [54.95533145418898,39.059217017199465],
        [54.955965529718775,39.059528438787346],
        [54.95651006989585,39.059695021087485],
        [54.95710708231753,39.05979186595441],
        [54.95770099884536,39.059711685026684],
        [54.958260950145515,39.05950812248438],
        [54.95944304052635,39.0587714232064],
        [54.9612278148164,39.057557757058774],
        [54.963136100656826,39.056268550275156],
        [54.96532441993053,39.05481670190883],
        [54.967524964405634,39.05330810419041],
        [54.96951838018762,39.051959906103924],
        [54.9702095714712,39.05363189887731],
        [54.970866805098815,39.055234154216315],
        [54.9710014094115,39.0554295290611],
        [54.97112058350893,39.055555166471855],
        [54.971405219657875,39.055854721055034],
        [54.97141829265508,39.05730762551557],
        [54.97364257542263,39.05882165577173],
        [54.974104722436685,39.05684323379797],
        [54.97443879436947,39.05620946729741],
        [54.97445336863655,39.05610789097111],
        [54.974530240809315,39.05610194033304],
        [54.9746064242102,39.05600981138946],
        [54.97465175066222,39.05592841128215],
        [54.976305267583584,39.05709598673887],
        [54.97651625273498,39.05818309592454],
        [54.97723730351848,39.058533531469045],
        [54.975754221998436,39.06722744507243],
        [54.97458582684246,39.07416182956154],
        [54.979532915486566,39.07671081722465],
        [54.98164141045702,39.07694629220328],
        [54.9819737052461,39.07526158523422],
        [54.982867929685185,39.07536336932303],
        [54.983253334074405,39.07303245976237],
        [54.98247876205702,39.07246107931517],
        [54.98161750385887,39.07040002396199],
        [54.98189722706048,39.06983452240994],
        [54.97994637050586,39.067921203369814],
        [54.97683836775552,39.066002519913575],
        [54.97803760362209,39.059062741179666],
        [54.97920600249558,39.05941317672417],
        [54.97963966359045,39.05823530848381],
        [54.98008566103557,39.057014524899266],
        [54.97983081730873,39.05611836876886],
        [54.98029966116713,39.0554956350077],
        [54.98069445440432,39.054829985902664],
        [54.98110466968369,39.0535957084869],
        [54.9821982944725,39.05421506813513],
        [54.982859984805394,39.05429798598],
        [54.98428205076509,39.05223758818784],
        [54.98331942171333,39.050837013813165],
        [54.98297058089002,39.05137789749593],
        [54.98252895210422,39.05056066087852],
        [54.982081148237256,39.04964150031913],
        [54.98224681271922,39.048571519594226],
        [54.98297133828917,39.04862620267464],
        [54.983054576093096,39.047918284797454],
        [54.98398612915935,39.04795620633001],
        [54.98436863353366,39.04695583501805],
        [54.98404932717889,39.04560807131338],
        [54.9844183598535,39.045301975791084],
        [54.98479972872528,39.04501733794081],
        [54.98485910572326,39.04432468062533],
        [54.98527603619353,39.043571168457554],
        [54.98524542348695,39.042684792659635],
        [54.985540498842504,39.041764983532815],
        [54.986112968899796,39.04231090734944],
        [54.98641153995412,39.03968708352236],
        [54.98580318140816,39.03880133113552],
        [54.986005844619676,39.038440418170964],
        [54.98611858052743,39.03814581195226],
        [54.98614494194021,39.03767954435633],
        [54.986065345066585,39.03713355896943],
        [54.98597957841818,39.03621206431929],
        [54.986050680913074,39.035752361787594],
        [54.98666470393674,39.034005198926494],
        [54.98716149844057,39.032467248369436],
        [54.98742385050187,39.03187343538607],
        [54.98776357028751,39.031490084173946],
        [54.98862848243333,39.03062970911116],
        [54.9891873986654,39.029553109207654],
        [54.99023553356092,39.0286490959591],
        [54.99101093927314,39.02799371168078],
        [54.99127215654252,39.02758195388605],
        [54.99185382751284,39.027614973745536],
        [54.99224027400987,39.02772804320011],
        [54.99246633310304,39.02793767217878],
        [54.99206840220717,39.02930664046562],
        [54.99372588058327,39.032616694727544],
        [54.99717818582404,39.032042910336806],
        [55.0001983293507,39.030770254243244],
        [54.99976560397234,39.027537547869166],
        [54.999912619884874,39.0266222700859],
        [54.99923017869572,39.025525350767836],
        [54.99966775479159,39.02419250122598],
        [54.99741623363615,39.021196682093375],
        [54.99626250983497,39.0220417862712],
        [54.996101981230325,39.022657457409395],
        [54.996009300786845,39.023112196005705],
        [54.995303611156345,39.02322922207929],
        [54.99463491876947,39.02347499418591],
        [54.993668372661894,39.023841182410855],
        [54.99270180317593,39.024110811110866],
        [54.99177395299385,39.02423049500998],
        [54.99187477922838,39.02353442305934],
        [54.99190668615521,39.0232561245183],
        [54.99186456863889,39.022977825977236],
        [54.991575807586834,39.02230451409243],
        [54.991231525243954,39.0215882868621],
        [54.99096360476724,39.02135067909543],
        [54.99068334471977,39.0214993094274],
        [54.9903402240034,39.02205437184699],
        [54.99000326933602,39.0225987054318],
        [54.989703326094656,39.02315376785238],
        [54.989427311093245,39.0237596373986],
        [54.98912661767474,39.02454789715759],
        [54.988488208685496,39.02653211244542],
        [54.98745955243453,39.028802079980245],
        [54.98649256513101,39.030921843810745],
        [54.98615339110548,39.031266803982376],
        [54.98583889276742,39.0313006279083],
        [54.98419994543852,39.0303245985512],
        [54.98492782488955,39.02674269261528],
        [54.98516717936502,39.02520984700395],
        [54.98488210403631,39.0230547289009],
        [54.98418119409663,39.02246801410024],
        [54.98224625231232,39.022052960674415],
        [54.979778102239884,39.019506172515854],
        [54.980333453386365,39.0182039293403],
        [54.9804321877695,39.017938329249475],
        [54.980407519244515,39.01766462113362],
        [54.979000652514664,39.01582974457473],
        [54.97856665401995,39.015262873538205],
        [54.97829309202576,39.01429903556849],
        [54.97828487362928,39.013345926434575],
        [54.978364070210596,39.012767447924425],
        [54.9785173159378,39.012188969414176],
        [54.97883409949519,39.010990854326565],
        [54.978897882253015,39.01061885961433],
        [54.9788197946278,39.01020376437294],
        [54.979841837452426,39.008269725862796],
        [54.97930279643176,39.0068380899038],
        [54.980800198049096,38.99998269569804],
        [54.98037043362518,38.998588611081125],
        [54.9757464059347,39.00671736415938],
        [54.97477962148872,39.00653906373314],
        [54.968479588452944,39.01675285443431],
        [54.96760738717007,39.01918295110853],
        [54.965932722450276,39.017965243522205],
        [54.96489399261874,39.02027808389606],
        [54.9648795575966,39.02190275697034],
        [54.964904249091646,39.021902756970846],
        [54.964472145849534,39.02344770936319],
        [54.96158002031477,39.02223465798816],
        [54.95802090670311,39.02091234326692],
        [54.95771220906359,39.013663010876705],
        [54.95620264248757,39.0127510598114],
        [54.95416845331399,39.01138209596859],
        [54.953302852371905,39.007391044384114],
        [54.954067345217396,39.0044404411441],
        [54.95314134267285,39.001216239701016],
    ]
};