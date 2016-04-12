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
"MIIBITANBgkqhkiG9w0BAQEFAAOCAQ4AMIIBCQKCAQBZQM9sX8M0PBHlNYO5iyHW\n"+
"/0La4UUIfLh1DlMy1lnyqlfLlRZCsyUkhzRaEAL5xrgo5qJFQvM3+CRYj4haaI4i\n"+
"GOvGe7CkdBgqGKR8EOtxHKO5lze5h474dcQodKUdK3YRpwu85fqQ8DRunTYt8O59\n"+
"+eIJhchW0tVP0LdT/x2nT9aFzxQh8g6yHT7ym4t5GrIjsapRsGZU7X0pH585HV2D\n"+
"/qpgfgnyL3sEHvN9vMRKIz+cj2JsAPu6w5s/j1hDVXvxF+C5tFYrvom9LF8C6cpQ\n"+
"PHzhI0hKAYEsV5psGqn1j1t7HA2+iSMsdPUEQqgM+IUoLaTGDFpQtgHmYi392UiB\n"+
"AgMBAAE=\n"+
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
	exports.item[1].amount="60";
	exports.item[2] = {};
	exports.item[2].libelle="EspaceNutrition - Consultation de suivi";
	exports.item[2].amount="50";
	exports.item[3] = {};
	exports.item[3].libelle="EspaceNutrition - Forfait bilan + 3 suivis";
	exports.item[3].amount="205";
	exports.item[4] = {};
	exports.item[4].libelle="EspaceNutrition - Forfait bilan + 5 suivis";
	exports.item[4].amount="300";
	

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


