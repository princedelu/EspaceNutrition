(function(exports){

    var config = {

        /* List all the roles you wish to use in the app
        * You have a max of 31 before the bit shift pushes the accompanying integer out of
        * the memory footprint for an integer
        */
        roles :[
            'public',
            'user',
            'admin'],

        /*
        Build out all the access levels you want referencing the roles listed above
        You can use the "*" symbol to represent access to all roles
         */
        accessLevels : {
            'public' : "*",
            'anon': ['public'],
            'userOnly' : ['user'],
            'user' : ['user', 'admin'],
            'admin': ['admin']
        }

    };

    exports.userRoles = buildRoles(config.roles);
    exports.accessLevels = buildAccessLevels(config.accessLevels, exports.userRoles);
	exports.publicKey=""+
"-----BEGIN PUBLIC KEY-----\n"+
"MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAj0uWKOlMXWpht6wvASYs\n"+
"esg+Dl/DuVlHFS/wX+FHS5EmFPh2MB+IsJSlyfk21Ozylpzq10U0omP6ADAMqDmK\n"+
"5iTTiyvAJkZJBtgpRPaujMtDctjV1O0ViWOe6+uphKRWdg/aMWtPBJXMnw/rDCCp\n"+
"OQGdkhAkWjUdsHnABZ38EiFlb0PJhp+jgyhrSgYldn8qBDB1X/YRRfy4QyTgmX6O\n"+
"/fu9Lj8mabZn4K7HNOSDjljTiUycG7VM5hLRKt3CKN/c50JTlJ9wzxLMbW1itm/U\n"+
"VuvQtvO1611vF9Vuusjy2qKum0IZI80eJbKR19916KBsUXoJnSitMH7cW+K7btiq\n"+
"5wIDAQAB\n"+
"-----END PUBLIC KEY-----";

	/* Element pour paypal
	*/
	exports.paypal = {};
	exports.paypal.business="angelique.guehl@espace-nutrition.fr";
	exports.paypal.urlReturn="http://www.espace-nutrition.fr/paiementSuccess";
	exports.paypal.urlCancel="http://www.espace-nutrition.fr";
	exports.paypal.urlNotify="http://www.espace-nutrition.fr/api/notifyPaiement";
	exports.paypal.sandbox=false;
	/* Informations sur les produits
	*/
	exports.item = {};
	exports.item[1] = {};
	exports.item[1].libelle="EspaceNutrition - Bilan nutritionnel";
	exports.item[1].amount="100";
	exports.item[2] = {};
	exports.item[2].libelle="EspaceNutrition - Consultation de suivi";
	exports.item[2].amount="60";
	exports.item[3] = {};
	exports.item[3].libelle="EspaceNutrition - Forfait bilan + 3 suivis";
	exports.item[3].amount="270";
	exports.item[4] = {};
	exports.item[4].libelle="EspaceNutrition - Forfait bilan + 5 suivis";
	exports.item[4].amount="380";
	

    /*
        Method to build a distinct bit mask for each role
        It starts off with "1" and shifts the bit to the left for each element in the
        roles array parameter
     */

    function buildRoles(roles){

        var bitMask = "01";
        var userRoles = {};

        for(var role in roles){
            var intCode = parseInt(bitMask, 2);
            userRoles[roles[role]] = {
                bitMask: intCode,
                title: roles[role]
            };
            bitMask = (intCode << 1 ).toString(2);
        }

        return userRoles;
    }

    /*
    This method builds access level bit masks based on the accessLevelDeclaration parameter which must
    contain an array for each access level containing the allowed user roles.
     */
    function buildAccessLevels(accessLevelDeclarations, userRoles){

        var accessLevels = {};
        for(var level in accessLevelDeclarations){

            var resultBitMask = '';
            var role;
            if(typeof accessLevelDeclarations[level] == 'string'){
                if(accessLevelDeclarations[level] == '*'){

                    for( role in userRoles){
                        resultBitMask += "1";
                    }
                    //accessLevels[level] = parseInt(resultBitMask, 2);
                    accessLevels[level] = {
                        bitMask: parseInt(resultBitMask, 2),
                        title: accessLevelDeclarations[level]
                    };
                }
                else console.log("Access Control Error: Could not parse '" + accessLevelDeclarations[level] + "' as access definition for level '" + level + "'");

            }
            else {

                resultBitMask = 0;
                for(role in accessLevelDeclarations[level]){
                    if(userRoles.hasOwnProperty(accessLevelDeclarations[level][role]))
                        resultBitMask = resultBitMask | userRoles[accessLevelDeclarations[level][role]].bitMask;
                    else console.log("Access Control Error: Could not find role '" + accessLevelDeclarations[level][role] + "' in registered roles while building access for '" + level + "'");
                }
                accessLevels[level] = {
                    bitMask: resultBitMask,
                    title: accessLevelDeclarations[level][role]
                };
            }
        }

        return accessLevels;
    }

})(typeof exports === 'undefined' ? this.routingConfig = {} : exports);


