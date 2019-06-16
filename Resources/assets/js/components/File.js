const File = (() => {

	let opt = {
		addMediaFields : $('.form-addModel-media input[type=file]')
	};

	function init()
	{
		console.log('Forms initialized');
		if(opt.addMediaFields.length){
			bindSelectFile()
		}
	}

	function bindSelectFile(elem)
	{
		opt.addMediaFields.change(function(){
			var fileName = $(this)[0].files[0].name;
			var nameInput = $(this).closest('form').find('input[name=name]');
			if(nameInput.val() == ''){
				nameInput.val(fileName);
			}
		});
	}

	return {
		init: init
	};

})();

export default File;