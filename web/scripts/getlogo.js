var getsheild = function(eadid, large = true) {
	switch (eadid) {
		case 'MTS.defender-individuals':
		case 'MTS.defender-organizations':
			// defender
			return large ? 1 : 2;
			break;

		case 'MTS.davis':
		case 'MTS.dunmore':
		case 'MTS.movingimage':
		case 'MTS.rollins':
			// dusable
			return large ? 1 : 2;
			break;

		case 'MTS.abbottsengstacke':
		case 'MTS.browning':
		case 'MTS.burns':
		case 'MTS.colter':
		case 'MTS.durham':
		case 'MTS.heritage':
		case 'MTS.path':
			// harsh
			return large ? 1 : 2;
			break;

		default:
			// library
			return large? 1 : 2;
			break;
	}
}
