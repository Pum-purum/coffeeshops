while getopts c:o:n: flag
do
    case "${flag}" in
        c) c=${OPTARG};;
        o) o=${OPTARG};;
        n) n=${OPTARG};;
    esac
done

openssl genrsa -out "./crt/${n}Key.pem" 4096
openssl req -new -key "./crt/${n}Key.pem" -out "./crt/${n}.csr" -subj "/CN=${c}/O=${o}"
openssl x509 -req -sha512 -days 365 -in "./crt/${n}.csr" -CA crt/CACert.pem -CAkey ca/CAKey.pem -CAcreateserial -out "./crt/${n}Cert.pem"
