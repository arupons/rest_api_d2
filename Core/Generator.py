from Templates import  Templates
import time
class Generator():
    def __init__(self, db, table, dbm):
        self.db = db
        self.table = table
        self.dbm = dbm

    def __init__(self):
        print ""
    def setRoot(self, root, folder):
        self.rootRoute = root
        self.controllerRoute = self.rootRoute + "/app/controllers" + folder
        self.serviceRoute = self.rootRoute + folder
        self.folder = folder

    def generate(self,db,table, dbm):
        try:
            template = Templates()
            # table = table.capitalize()
            table= table[:1].upper() + table[1:]
            fecha = time.strftime("%x")
            hora = time.strftime("%X")
            controller = open(self.controllerRoute + table + "sController.php", "w")
            controller.write(template.getController(fecha, hora, db, table, dbm))
            service = open(self.serviceRoute + "/" + table + "s.php", "w")
            service.write(template.getService(fecha, hora, table, self.folder))
            result = 1
        except Exception as e:
            print "\nError: {0} ".format(e)
            result = 0
        return "ok"