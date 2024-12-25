<!DOCTYPE html>
<html lang="en" style="box-sizing: border-box;">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Welcome</title>

    <style>
        body {
            background-color: #fee1e1;
        }

        .card__outer-year span:nth-child(1):after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 100%;
            border-bottom: 2px solid #fff;
        }

        .card__outer-year span:nth-child(4):after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 100%;
            border-bottom: 2px solid #fff;
        }

        .card__comet:before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            background: linear-gradient(to bottom, rgba(255, 255, 255, 0.2) 0%, rgba(255, 255, 255, 0.2) 27%, rgba(255, 255, 255, 0) 100%);
            border-radius: 20px;
            transform: rotate(-45deg);
        }

        .card__comet:after {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            background: linear-gradient(to bottom, rgba(255, 255, 255, 0.2) 0%, rgba(255, 255, 255, 0.2) 27%, rgba(255, 255, 255, 0) 100%);
            border-radius: 20px;
            transform: rotate(-45deg);
        }

        .card__comet:before {
            width: 18px;
            height: 70px;
            transform-origin: -2px 13px;
        }

        .card__comet:after {
            width: 12px;
            height: 80px;
            transform-origin: 0px 8px;
        }
    </style>
</head>

<body style="box-sizing: border-box;" bgcolor="#fee1e1">
    <div class="wrapper"
        style="box-sizing: border-box; position: absolute; left: 50%; top: 50%; height: 342px; width: 655px; margin: -171px 0 0 -327.5px;">
        <div class="card"
            style="box-sizing: border-box; display: flex; justify-content: center; width: 655px; height: 342px; border-radius: 5px; position: absolute; box-shadow: -20px 30px 116px 0 rgba(92, 15, 15, 0.54); overflow: hidden; z-index: 4; background: url(https://s3-us-west-2.amazonaws.com/s.cdpn.io/279756/2017_bg.png) 0% 0% / cover;">
            <div class="card__year"
                style="  user-select: none;box-sizing: border-box; font-family: 'Oswald', sans-serif; color: #fff; font-size: 110px; line-height: 110px; font-weight: 100; position: relative; z-index: 10; padding: 55px 0;"
                align="center">
                Welcome
            </div>
            <div class="card__cometOuter" style="box-sizing: border-box; position: absolute; top: 30%; left: 25%;">
                <div class="card__comet"
                    style="box-sizing: border-box; position: relative; width: 8px; height: 8px; background-color: #fff; border-radius: 100%;">
                </div>
                <div class="card__comet card__comet--second"
                    style="box-sizing: border-box; position: relative; width: 8px; height: 8px; background-color: #fff; border-radius: 100%; right: -30px; top: -15px; transform: scale(0.6);">
                </div>
            </div>
            <div class="card__circle"
                style="box-sizing: border-box; position: absolute; border-radius: 100%; background-image: linear-gradient(-239deg, #3B4576 0%, #242A48 59%); box-shadow: -10px -15px 90px 0 #191C41; z-index: 2; right: 68px; bottom: 34px; width: 230px; height: 230px;">
            </div>
            <div class="card__smallCircle"
                style="box-sizing: border-box; position: absolute; border-radius: 100%; background-image: linear-gradient(-239deg, #3B4576 0%, #242A48 59%); box-shadow: -10px -15px 90px 0 #191C41; z-index: 2; right: 40%; top: -7%; width: 50px; height: 50px;">
            </div>
            <div class="card__orangeShine"
                style="box-sizing: border-box; position: absolute; right: -150px; top: -90px; bottom: 50px; z-index: 2; width: 570px; height: 500px; background: url(https://s3-us-west-2.amazonaws.com/s.cdpn.io/279756/orange_shine.png) no-repeat 0% 0% / cover;">
            </div>
            <div class="card__greenShine"
                style="box-sizing: border-box; position: absolute; left: 20%; top: 0; bottom: 0; z-index: 1; width: 400px; background: url(https://s3-us-west-2.amazonaws.com/s.cdpn.io/279756/green_shine.png) no-repeat 0% 0% / cover;">
            </div>
            <div class="card__thankyou"
                style="box-sizing: border-box; font-family: 'Oswald', sans-serif; position: absolute; text-transform: uppercase; font-weight: 100; bottom: 20%; z-index: 2; color: rgba(255, 255, 255, 0.5); letter-spacing: 5px; line-height: 17px; font-size: 22px;"
                align="center">
                {{ $msg }}
            </div>

        </div>
    </div>
    <script>
        const $circle = document.querySelector('.card__circle');
const $smallCircle = document.querySelector('.card__smallCircle');
const $year = document.querySelector('.card__year');
const $card = document.querySelector('.card');
const $cardOrangeShine = document.querySelector('.card__orangeShine');
const $cardThankYou = document.querySelector('.card__thankyou');
const $cardComet = document.querySelector('.card__cometOuter');

const generateTranslate = (el, e, value) => {
	el.style.transform = `translate(${e.clientX*value}px, ${e.clientY*value}px)`;
}
// http://stackoverflow.com/a/1480137
const cumulativeOffset = (element) => {
    var top = 0, left = 0;
    do {
        top += element.offsetTop  || 0;
        left += element.offsetLeft || 0;
        element = element.offsetParent;
    } while(element);

    return {
        top: top,
        left: left
    };
};
document.onmousemove = (event) => {
	const e = event || window.event;
	const x = (e.pageX - cumulativeOffset($card).left - (350 / 2)) * -1 / 100;
	const y = (e.pageY - cumulativeOffset($card).top - (350 / 2)) * -1 / 100;

	const matrix = [
		[1, 0, 0, -x * 0.00005],
		[0, 1, 0, -y * 0.00005],
		[0, 0, 1, 1],
		[0, 0, 0, 1]
	];

	generateTranslate($smallCircle, e, 0.03);
	generateTranslate($cardThankYou, e, 0.03);
	generateTranslate($cardOrangeShine, e, 0.09);
	generateTranslate($circle, e, 0.05);
	generateTranslate($year, e, 0.03);
	generateTranslate($cardComet, e, 0.05);

	$card.style.transform = `matrix3d(${matrix.toString()})`;
}
    </script>
</body>
</html>