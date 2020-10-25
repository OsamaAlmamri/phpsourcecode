unit unV8Extension;
{$I cef.inc}

interface

uses
  uCEFInterfaces, uCEFProcessMessage, uCEFv8Context, uCEFTypes, unConfig,
  Generics.Collections, Windows,SysUtils;

type
  TV8Extension = class
    // ��ʾ����
    class procedure show(url: string; width: Integer; height: Integer);
    // modal��ʾ����
    class procedure showmodal(url: string; width: Integer; height: Integer);
    // ���������ڷ�����Ϣ
    class procedure sendMsg(aMsg,aFrameName: string);
  end;

implementation

{ TV8Extension }

class procedure TV8Extension.sendMsg(aMsg,aFrameName: string);
var
  processIDList: TList<DWORD>;
  i: Integer;
  msg: ICefProcessMessage;
begin
  msg := TCefProcessMessageRef.New(YS_BROWSER_EXTENSION_WINDOW_MSG);
  msg.ArgumentList.SetString(0, aMsg);
  msg.ArgumentList.SetString(1, aFrameName);
  TCefv8ContextRef.Current.Browser.SendProcessMessage(PID_BROWSER, msg);
end;

class procedure TV8Extension.show(url: string; width: Integer; height: Integer);
var
  msg: ICefProcessMessage;
begin
  msg := TCefProcessMessageRef.New(YS_BROWSER_EXTENSION_SHOW);
  msg.ArgumentList.SetString(0, url);
  msg.ArgumentList.SetInt(1, width);
  msg.ArgumentList.SetInt(2, height);

  TCefv8ContextRef.Current.Browser.SendProcessMessage(PID_BROWSER, msg);

end;

class procedure TV8Extension.showmodal(url: string; width: Integer; height: Integer);
var
  msg: ICefProcessMessage;
begin
  msg := TCefProcessMessageRef.New(YS_BROWSER_EXTENSION_SHOWMODAL);
  msg.ArgumentList.SetString(0, url);
  msg.ArgumentList.SetInt(1, width);
  msg.ArgumentList.SetInt(2, height);

  TCefv8ContextRef.Current.Browser.SendProcessMessage(PID_BROWSER, msg);

end;

end.

