from Generator import Generator
import sys

generador = Generator()
root = "/srv/http/Services"
folder = "/WSContratos/"
# folder = "/WSCatalogos/"
if __name__ == "__main__":
    """Executes if the script is run as main script (for testing purposes)"""
    print "Generando Servicio: " + sys.argv[1]+"."+sys.argv[2]
    print "DBM: "+sys.argv[3]
    print "Folder: "+sys.argv[4]

    generador.setRoot(root, '/'+sys.argv[4]+'/')
    if (generador.generate(sys.argv[1], sys.argv[2], sys.argv[3])):
        print "Generado Exitosamente"
    else:
        print "Generacion Fallida, Intente Nuevamente"