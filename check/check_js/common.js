

//Проверка текст боксов

//<textarea id="txt" name = "pt_text" rows = "8" cols = "8" class = "input" WRAP ></textarea>

function val()
{
  if (trimAll(document.getElementById('txt').value) === '')
  {
     alert('Empty !!');
  }
} 

function trimAll(sString)
{
    while (sString.substring(0,1) == ' ')
    {
        sString = sString.substring(1, sString.length);
    }
    while (sString.substring(sString.length-1, sString.length) == ' ')
    {
        sString = sString.substring(0,sString.length-1);
    }
return sString;
}